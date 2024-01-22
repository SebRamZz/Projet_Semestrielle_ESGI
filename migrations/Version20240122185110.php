<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122185110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE invoice_detail_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE formula_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contract_detail_id_seq CASCADE');
        $this->addSql('ALTER TABLE contract_detail DROP CONSTRAINT fk_12469ade2576e0fd');
        $this->addSql('ALTER TABLE invoice_detail DROP CONSTRAINT fk_9530e2c02989f1fd');
        $this->addSql('ALTER TABLE formula_product DROP CONSTRAINT fk_cc9db811a50a6386');
        $this->addSql('ALTER TABLE formula_product DROP CONSTRAINT fk_cc9db8114584665a');
        $this->addSql('ALTER TABLE formula DROP CONSTRAINT fk_67315881ebf3dfe7');
        $this->addSql('DROP TABLE contract_detail');
        $this->addSql('DROP TABLE invoice_detail');
        $this->addSql('DROP TABLE formula_product');
        $this->addSql('DROP TABLE formula');
        $this->addSql('ALTER TABLE contract DROP slug');
        $this->addSql('ALTER TABLE invoice DROP slug');
        $this->addSql('ALTER TABLE product ADD product_hour INT NULL');
        $this->addSql('ALTER TABLE product ADD product_price INT NULL');
        $this->addSql('ALTER TABLE product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NULL');
        $this->addSql('ALTER TABLE product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NULL');
        $this->addSql('ALTER TABLE product ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD validity_date TIMESTAMP(0) WITHOUT TIME ZONE NULL');
        $this->addSql('ALTER TABLE product DROP hour');
        $this->addSql('ALTER TABLE product DROP price');
        $this->addSql('ALTER TABLE product DROP slug');
        $this->addSql('ALTER TABLE product RENAME COLUMN name TO product_name');
        $this->addSql('ALTER TABLE product RENAME COLUMN description TO product_description');
        $this->addSql('COMMENT ON COLUMN product.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.validity_date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE invoice_detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE formula_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contract_detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contract_detail (id INT NOT NULL, contract_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, hour INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_12469ade2576e0fd ON contract_detail (contract_id)');
        $this->addSql('CREATE TABLE invoice_detail (id INT NOT NULL, invoice_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, description TEXT NOT NULL, hour INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9530e2c02989f1fd ON invoice_detail (invoice_id)');
        $this->addSql('CREATE TABLE formula_product (formula_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(formula_id, product_id))');
        $this->addSql('CREATE INDEX idx_cc9db8114584665a ON formula_product (product_id)');
        $this->addSql('CREATE INDEX idx_cc9db811a50a6386 ON formula_product (formula_id)');
        $this->addSql('CREATE TABLE formula (id INT NOT NULL, driving_school_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, type_license_driving VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_67315881ebf3dfe7 ON formula (driving_school_id)');
        $this->addSql('ALTER TABLE contract_detail ADD CONSTRAINT fk_12469ade2576e0fd FOREIGN KEY (contract_id) REFERENCES contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_detail ADD CONSTRAINT fk_9530e2c02989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formula_product ADD CONSTRAINT fk_cc9db811a50a6386 FOREIGN KEY (formula_id) REFERENCES formula (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formula_product ADD CONSTRAINT fk_cc9db8114584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE formula ADD CONSTRAINT fk_67315881ebf3dfe7 FOREIGN KEY (driving_school_id) REFERENCES driving_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD hour INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP product_hour');
        $this->addSql('ALTER TABLE product DROP product_price');
        $this->addSql('ALTER TABLE product DROP created_at');
        $this->addSql('ALTER TABLE product DROP updated_at');
        $this->addSql('ALTER TABLE product DROP deleted_at');
        $this->addSql('ALTER TABLE product DROP validity_date');
        $this->addSql('ALTER TABLE product RENAME COLUMN product_name TO name');
        $this->addSql('ALTER TABLE product RENAME COLUMN product_description TO description');
        $this->addSql('ALTER TABLE invoice ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contract ADD slug VARCHAR(255) DEFAULT NULL');
    }
}
