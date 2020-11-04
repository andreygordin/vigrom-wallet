<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Validator\Constraint;

use Doctrine\DBAL\Connection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CurrencyExistsValidator extends ConstraintValidator
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CurrencyExists) {
            throw new UnexpectedTypeException($constraint, CurrencyExists::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $currencyExists = $this->connection
                ->fetchOne(
                    'SELECT 1 FROM currency WHERE code = :code',
                    [':code' => $value]
                ) !== false;

        if (!$currencyExists) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(CurrencyExists::CURRENCY_DOESNT_EXIST_ERROR)
                ->addViolation();
        }
    }
}
