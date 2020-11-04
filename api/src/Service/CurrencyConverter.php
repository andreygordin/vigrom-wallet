<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Service;

use Webmozart\Assert\Assert;

class CurrencyConverter
{
    private float $sourceRate;
    private float $targetRate;

    public function __construct(float $sourceRate, float $targetRate)
    {
        Assert::greaterThan($sourceRate, 0);
        Assert::greaterThan($targetRate, 0);

        $this->sourceRate = $sourceRate;
        $this->targetRate = $targetRate;
    }

    public function convert(int $value): int
    {
        Assert::greaterThanEq($value, 0);

        $result = $value * $this->sourceRate / $this->targetRate;
        return (int)round($result);
    }
}
