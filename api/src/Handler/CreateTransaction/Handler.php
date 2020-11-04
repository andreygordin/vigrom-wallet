<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Handler\CreateTransaction;

use App\Exception\DomainNotFoundException;
use App\Service\CurrencyConverter;
use Doctrine\DBAL\Connection;
use DomainException;
use Exception;

class Handler
{
    private Connection $connection;

    private const TRANSACTION_TYPE_DEBIT = 'debit';
    private const TRANSACTION_TYPE_CREDIT = 'credit';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(Command $command): void
    {
        /** @var string|false $walletCurrency */
        $walletCurrency = $this->connection->fetchOne(
            'SELECT currency_code FROM wallet WHERE id = :id',
            [':id' => $command->getWalletId()]
        );
        if ($walletCurrency === false) {
            throw new DomainNotFoundException('Wallet not found.');
        }

        if ($command->getCurrency() !== $walletCurrency) {
            /** @psalm-var array<string, array{rate: float}> $data */
            $currencyData = $this->connection
                ->fetchAllAssociativeIndexed(
                    'SELECT code, rate FROM currency WHERE code IN (:sourceCurrency, :targetCurrency)',
                    [':sourceCurrency' => $command->getCurrency(), ':targetCurrency' => $walletCurrency],
                );
            $sourceRate = (float)$currencyData[$command->getCurrency()]['rate'];
            $targetRate = (float)$currencyData[$walletCurrency]['rate'];
            $converter = new CurrencyConverter($sourceRate, $targetRate);
            $convertedAmount = $converter->convert($command->getAmount());
        } else {
            $convertedAmount = $command->getAmount();
        }

        $this->connection->beginTransaction();

        try {
            $sql = $command->getTransactionType() === self::TRANSACTION_TYPE_DEBIT
                ? 'UPDATE wallet SET balance = balance + :amount WHERE id = :id'
                : 'UPDATE wallet SET balance = balance - :amount WHERE id = :id AND balance >= :amount';

            $affectedRows = $this->connection->executeStatement(
                $sql,
                [':amount' => $convertedAmount, ':id' => $command->getWalletId()]
            );

            if ($affectedRows === 0) {
                throw new DomainException('Not enough money.');
            }

            $this->connection->executeStatement(
                '
                        INSERT INTO transaction
                            (wallet_id, currency_code, original_amount, converted_amount, type, reason)
                            VALUES (:walletId, :currencyCode, :originalAmount, :convertedAmount, :type, :reason)
                    ',
                [
                    ':walletId' => $command->getWalletId(),
                    ':currencyCode' => $command->getCurrency(),
                    ':originalAmount' => $command->getAmount(),
                    ':convertedAmount' => $convertedAmount,
                    ':type' => $command->getTransactionType(),
                    ':reason' => $command->getReason(),
                ]
            );

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
