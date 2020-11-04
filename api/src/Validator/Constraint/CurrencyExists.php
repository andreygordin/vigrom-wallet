<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CurrencyExists extends Constraint
{
    public const CURRENCY_DOESNT_EXIST_ERROR = '6d971ae6-a987-4764-8a94-d806147489f2';

    public string $message = 'Currency doesn\'t exist';
}
