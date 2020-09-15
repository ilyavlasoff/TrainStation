<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915223640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE benefits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bonuses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE job_title_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE monitoring_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE station_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE train_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE usr_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE voyage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wagon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wagon_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE way_through_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE benefits (id INT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, valid_before TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_docs VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_965A49FEA76ED395 ON benefits (user_id)');
        $this->addSql('CREATE TABLE bonuses (id INT NOT NULL, user_id INT DEFAULT NULL, quantity INT NOT NULL, added_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8535CFD2A76ED395 ON bonuses (user_id)');
        $this->addSql('CREATE TABLE job_title (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A6AC51BA76ED395 ON job_title (user_id)');
        $this->addSql('CREATE TABLE monitoring (id INT NOT NULL, train_id INT DEFAULT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA4F975D23BCD4D0 ON monitoring (train_id)');
        $this->addSql('CREATE TABLE station (id INT NOT NULL, address VARCHAR(100) NOT NULL, phone VARCHAR(11) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, status_id INT DEFAULT NULL, source_station INT DEFAULT NULL, destination_station INT DEFAULT NULL, user_id INT DEFAULT NULL, voyage_id INT DEFAULT NULL, wagon_id INT DEFAULT NULL, price NUMERIC(10, 0) NOT NULL, place INT NOT NULL, route_length DOUBLE PRECISION NOT NULL, price_for_km NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA36BF700BD ON ticket (status_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3D6E640D9 ON ticket (source_station)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3F100C7C9 ON ticket (destination_station)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A76ED395 ON ticket (user_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA368C9E5AF ON ticket (voyage_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A21C43DD ON ticket (wagon_id)');
        $this->addSql('CREATE TABLE ticket_status (id INT NOT NULL, status_description VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE train (id INT NOT NULL, route VARCHAR(255) NOT NULL, train_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE usr (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, two_factor_authentication BOOLEAN DEFAULT NULL, two_factor_code VARCHAR(255) DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, surname VARCHAR(50) DEFAULT NULL, patronymic VARCHAR(50) DEFAULT NULL, passport_data VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1762498CE7927C74 ON usr (email)');
        $this->addSql('CREATE TABLE voyage (id INT NOT NULL, train_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, department_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F9D895523BCD4D0 ON voyage (train_id)');
        $this->addSql('CREATE TABLE wagon (id INT NOT NULL, train_id INT DEFAULT NULL, wagon_type_id INT DEFAULT NULL, places_count INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BBDBD3623BCD4D0 ON wagon (train_id)');
        $this->addSql('CREATE INDEX IDX_BBDBD36628028CE ON wagon (wagon_type_id)');
        $this->addSql('CREATE TABLE wagon_type (id INT NOT NULL, type_description VARCHAR(50) NOT NULL, price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE way_through (id INT NOT NULL, train_id INT DEFAULT NULL, station_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75859E7223BCD4D0 ON way_through (train_id)');
        $this->addSql('CREATE INDEX IDX_75859E7221BDB235 ON way_through (station_id)');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FEA76ED395 FOREIGN KEY (user_id) REFERENCES usr (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bonuses ADD CONSTRAINT FK_8535CFD2A76ED395 FOREIGN KEY (user_id) REFERENCES usr (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE job_title ADD CONSTRAINT FK_2A6AC51BA76ED395 FOREIGN KEY (user_id) REFERENCES usr (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE monitoring ADD CONSTRAINT FK_BA4F975D23BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36BF700BD FOREIGN KEY (status_id) REFERENCES ticket_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3D6E640D9 FOREIGN KEY (source_station) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F100C7C9 FOREIGN KEY (destination_station) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES usr (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA368C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A21C43DD FOREIGN KEY (wagon_id) REFERENCES wagon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D895523BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wagon ADD CONSTRAINT FK_BBDBD3623BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wagon ADD CONSTRAINT FK_BBDBD36628028CE FOREIGN KEY (wagon_type_id) REFERENCES wagon_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE way_through ADD CONSTRAINT FK_75859E7223BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE way_through ADD CONSTRAINT FK_75859E7221BDB235 FOREIGN KEY (station_id) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3D6E640D9');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3F100C7C9');
        $this->addSql('ALTER TABLE way_through DROP CONSTRAINT FK_75859E7221BDB235');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA36BF700BD');
        $this->addSql('ALTER TABLE monitoring DROP CONSTRAINT FK_BA4F975D23BCD4D0');
        $this->addSql('ALTER TABLE voyage DROP CONSTRAINT FK_3F9D895523BCD4D0');
        $this->addSql('ALTER TABLE wagon DROP CONSTRAINT FK_BBDBD3623BCD4D0');
        $this->addSql('ALTER TABLE way_through DROP CONSTRAINT FK_75859E7223BCD4D0');
        $this->addSql('ALTER TABLE benefits DROP CONSTRAINT FK_965A49FEA76ED395');
        $this->addSql('ALTER TABLE bonuses DROP CONSTRAINT FK_8535CFD2A76ED395');
        $this->addSql('ALTER TABLE job_title DROP CONSTRAINT FK_2A6AC51BA76ED395');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3A76ED395');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA368C9E5AF');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3A21C43DD');
        $this->addSql('ALTER TABLE wagon DROP CONSTRAINT FK_BBDBD36628028CE');
        $this->addSql('DROP SEQUENCE benefits_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bonuses_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE job_title_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE monitoring_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE station_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE train_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE usr_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE voyage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wagon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wagon_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE way_through_id_seq CASCADE');
        $this->addSql('DROP TABLE benefits');
        $this->addSql('DROP TABLE bonuses');
        $this->addSql('DROP TABLE job_title');
        $this->addSql('DROP TABLE monitoring');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_status');
        $this->addSql('DROP TABLE train');
        $this->addSql('DROP TABLE usr');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('DROP TABLE wagon');
        $this->addSql('DROP TABLE wagon_type');
        $this->addSql('DROP TABLE way_through');
    }
}
