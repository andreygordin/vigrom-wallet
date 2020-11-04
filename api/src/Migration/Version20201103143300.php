<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201103143300 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            CREATE TABLE currency (
                code VARCHAR(3) PRIMARY KEY,
                rate FLOAT NOT NULL
            );
        '
        );

        $this->addSql(
            '
            CREATE TABLE wallet (
                id SERIAL PRIMARY KEY,
                currency_code VARCHAR(3) NOT NULL REFERENCES currency(code),
                balance INT NOT NULL
            );
        '
        );

        $this->addSql("CREATE TYPE TRANSACTION_TYPE AS ENUM ('debit', 'credit');");
        $this->addSql("CREATE TYPE TRANSACTION_REASON AS ENUM ('stock', 'refund');");
        $this->addSql(
            '
            CREATE TABLE transaction (
                id SERIAL PRIMARY KEY,
                wallet_id INT NOT NULL REFERENCES wallet(id) ON DELETE CASCADE ON UPDATE CASCADE,
                currency_code VARCHAR(3) NOT NULL REFERENCES currency(code),
                original_amount INT NOT NULL,
                converted_amount INT NOT NULL,
                type TRANSACTION_TYPE NOT NULL,
                reason TRANSACTION_REASON NOT NULL,
                executed_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
            );
        '
        );
        $this->addSql('CREATE INDEX transaction_wallet_id_idx ON transaction(wallet_id)');
        $this->addSql('CREATE INDEX transaction_reason_idx ON transaction(reason)');

        $this->addSql(
            "
            INSERT INTO currency VALUES ('USD', 1), ('RUB', 0.012620);
        "
        );
        $this->addSql(
            "
            INSERT INTO wallet VALUES (241, 'USD', 50000), (242, 'RUB', 1000000);
        "
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE currency');
        $this->addSql("DROP TYPE TRANSACTION_TYPE;");
        $this->addSql("DROP TYPE TRANSACTION_REASON;");
    }
}
