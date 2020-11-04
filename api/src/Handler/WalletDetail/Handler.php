<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Handler\WalletDetail;

use App\Exception\DomainNotFoundException;
use Doctrine\DBAL\Connection;

class Handler
{
    private Connection $connection;
    private Denormalizer $denormalizer;

    public function __construct(Connection $connection, Denormalizer $denormalizer)
    {
        $this->connection = $connection;
        $this->denormalizer = $denormalizer;
    }

    public function handle(Query $query): Response
    {
        $result = $this->connection
            ->fetchAssociative(
                'SELECT id, currency_code, balance FROM wallet WHERE id = :id',
                [':id' => $query->walletId]
            );

        if ($result === false) {
            throw new DomainNotFoundException('Wallet not found.');
        }

        return $this->denormalizer->denormalize($result);
    }
}
