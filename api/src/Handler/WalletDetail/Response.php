<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Handler\WalletDetail;

class Response
{
    public int $id = 0;
    public string $currencyCode = '';
    public int $balance = 0;
}
