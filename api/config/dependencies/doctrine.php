<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

return [
    Connection::class => function (ContainerInterface $container): Connection {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{
         *     driver: string,
         *     host: string,
         *     user: string,
         *     password: string,
         *     dbname: string,
         *     charset: string,
         * } $settings
         */
        $settings = $container->get('config')['doctrine'];
        return DriverManager::getConnection($settings);
    },

    'config' => [
        'doctrine' => [
            'driver' => 'pdo_pgsql',
            'host' => getenv('DB_HOST'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
            'charset' => 'utf-8',
        ],
    ],
];
