<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311120814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Создаем последовательности для автоинкремента
        $this->addSql('CREATE SEQUENCE product_coupons_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tax_rates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        // Создаем таблицы с автоинкрементом для id
        $this->addSql('CREATE TABLE product_coupons (
            id INT NOT NULL DEFAULT nextval(\'product_coupons_id_seq\'),
            code VARCHAR(255) NOT NULL,
            discount DOUBLE PRECISION NOT NULL,
            type VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE products (
            id INT NOT NULL DEFAULT nextval(\'products_id_seq\'),
            name VARCHAR(255) NOT NULL,
            price DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE tax_rates (
            id INT NOT NULL DEFAULT nextval(\'tax_rates_id_seq\'),
            country_code VARCHAR(2) NOT NULL,
            rate DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        )');

        // Индекс для tax_rates
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7AE5E1DF026BB7C ON tax_rates (country_code)');
    }

    public function down(Schema $schema): void
    {
        // Откатываем миграцию, удаляя все таблицы и последовательности
        $this->addSql('DROP SEQUENCE product_coupons_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_rates_id_seq CASCADE');
        $this->addSql('DROP TABLE product_coupons');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE tax_rates');
    }
}