<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406105828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, salary_supplement_type SMALLINT NOT NULL, salary_supplement_percents INT DEFAULT NULL, salary_supplement_fixed_value_amount_in_cents INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN department.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE employee (id UUID NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN employee.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE employment (employee_id UUID NOT NULL, department_id UUID DEFAULT NULL, employment_date DATE NOT NULL, salary_amount_in_cents INT DEFAULT NULL, PRIMARY KEY(employee_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF089C98AE80F5DF ON employment (department_id)');
        $this->addSql('COMMENT ON COLUMN employment.employee_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employment.department_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE employment ADD CONSTRAINT FK_BF089C988C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employment ADD CONSTRAINT FK_BF089C98AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employment DROP CONSTRAINT FK_BF089C98AE80F5DF');
        $this->addSql('ALTER TABLE employment DROP CONSTRAINT FK_BF089C988C03F15C');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE employment');
    }
}
