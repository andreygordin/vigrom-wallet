<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

use App\Http\Action\CreateTransactionAction;
use App\Http\Action\WalletDetailAction;
use Slim\App;

return static function (App $app): void {
    $app->post(
        '/wallets/{id:\d+}/transactions',
        CreateTransactionAction::class
    );
    $app->get(
        '/wallets/{id:\d+}',
        WalletDetailAction::class
    );
};
