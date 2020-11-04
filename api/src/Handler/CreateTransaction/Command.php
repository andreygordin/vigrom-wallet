<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Handler\CreateTransaction;

use App\Validator\Constraint\CurrencyExists;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\Positive()
     */
    private int $walletId = 0;

    /**
     * @Assert\Choice(
     *     choices={"debit", "credit"},
     *     message="Only these transaction types are allowed: 'debit', 'credit'"
     * )
     */
    private string $transactionType = '';

    /**
     * @Assert\Positive(message="Amount must be a positive number")
     */
    private int $amount = 0;

    /**
     * @Assert\Sequentially({
     *  @Assert\NotBlank(),
     *  @CurrencyExists(message="Currency doesn't exist")
     * })
     */
    private string $currency = '';

    /**
     * @Assert\Choice(
     *     choices={"stock", "refund"},
     *     message="Only these reasons are allowed: 'stock', 'refund'"
     * )
     */
    private string $reason = '';

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    /**
     * @param mixed $value
     */
    public function setWalletId($value): void
    {
        $this->walletId = (int)$value;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $value
     */
    public function setTransactionType($value): void
    {
        $this->transactionType = (string)$value;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param mixed $value
     */
    public function setAmount($value): void
    {
        $this->amount = (int)$value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param mixed $value
     */
    public function setCurrency($value): void
    {
        $this->currency = (string)$value;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param mixed $value
     */
    public function setReason($value): void
    {
        $this->reason = (string)$value;
    }
}
