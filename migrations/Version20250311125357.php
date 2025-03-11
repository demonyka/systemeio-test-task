<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311125357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert default products, coupons, and tax rates.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO products (name, price) VALUES ('Iphone', 100)");
        $this->addSql("INSERT INTO products (name, price) VALUES ('Наушники', 20)");
        $this->addSql("INSERT INTO products (name, price) VALUES ('Чехол', 10)");

        $this->addSql("INSERT INTO product_coupons (code, discount, type) VALUES ('P10', 10, 'percentage')");
        $this->addSql("INSERT INTO product_coupons (code, discount, type) VALUES ('P100', 100, 'fixed')");

        $this->addSql("INSERT INTO tax_rates (country_code, rate) VALUES ('DE', 19)");
        $this->addSql("INSERT INTO tax_rates (country_code, rate) VALUES ('IT', 22)");
        $this->addSql("INSERT INTO tax_rates (country_code, rate) VALUES ('GR', 24)");
        $this->addSql("INSERT INTO tax_rates (country_code, rate) VALUES ('FR', 20)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM products WHERE name IN ('Iphone', 'Наушники', 'Чехол')");
        $this->addSql("DELETE FROM product_coupons WHERE code IN ('P10', 'P100')");
        $this->addSql("DELETE FROM tax_rates WHERE country_code IN ('DE', 'IT', 'GR', 'FR')");
    }
}
