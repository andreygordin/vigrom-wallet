<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Handler\WalletDetail;

use Webmozart\Assert\Assert;

class Denormalizer
{
    public function denormalize(array $data): Response
    {
        /**
         * @psalm-var array{
         *     id: int,
         *     currency_code: string,
         *     balance: int,
         * } $data
         */

        $keys = ['id', 'currency_code', 'balance'];
        foreach ($keys as $key) {
            Assert::keyExists($data, $key);
        }

        $response = new Response();
        $response->id = $data['id'];
        $response->currencyCode = $data['currency_code'];
        $response->balance = $data['balance'];

        return $response;
    }
}
