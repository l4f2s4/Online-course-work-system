<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721092046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, deptclass_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_169E6FB9A526685A (deptclass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, stundent_name_r_id INT DEFAULT NULL, test1 DOUBLE PRECISION DEFAULT NULL, test2 DOUBLE PRECISION DEFAULT NULL, individual1 DOUBLE PRECISION DEFAULT NULL, individual2 DOUBLE PRECISION DEFAULT NULL, group_work DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, percent DOUBLE PRECISION DEFAULT NULL, signstatus DOUBLE PRECISION DEFAULT NULL, addstatus VARCHAR(255) DEFAULT NULL, add_register VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, reported VARCHAR(255) DEFAULT NULL, INDEX IDX_136AC113C0DC136F (stundent_name_r_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result_subject_year (result_id INT NOT NULL, subject_year_id INT NOT NULL, INDEX IDX_CF3D3F797A7B643 (result_id), INDEX IDX_CF3D3F79E5435205 (subject_year_id), PRIMARY KEY(result_id, subject_year_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, sdept_id INT DEFAULT NULL, add_student_class_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, regno VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, phoneno INT DEFAULT NULL, yo_s VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, INDEX IDX_B723AF33E4B27423 (sdept_id), INDEX IDX_B723AF3312941BDC (add_student_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_subject_year (student_id INT NOT NULL, subject_year_id INT NOT NULL, INDEX IDX_B096465BCB944F1A (student_id), INDEX IDX_B096465BE5435205 (subject_year_id), PRIMARY KEY(student_id, subject_year_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_year (id INT AUTO_INCREMENT NOT NULL, subjectname_id INT DEFAULT NULL, steacher_id INT DEFAULT NULL, semester VARCHAR(255) NOT NULL, syear VARCHAR(255) NOT NULL, INDEX IDX_2948F7EC3DB272 (subjectname_id), INDEX IDX_2948F7ECAE4D9375 (steacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_year_course (subject_year_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_96839EC4E5435205 (subject_year_id), INDEX IDX_96839EC4591CC992 (course_id), PRIMARY KEY(subject_year_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, add_department_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, martalstatus VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, phoneno INT DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, userimage VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649C8F54046 (add_department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9A526685A FOREIGN KEY (deptclass_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113C0DC136F FOREIGN KEY (stundent_name_r_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE result_subject_year ADD CONSTRAINT FK_CF3D3F797A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result_subject_year ADD CONSTRAINT FK_CF3D3F79E5435205 FOREIGN KEY (subject_year_id) REFERENCES subject_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33E4B27423 FOREIGN KEY (sdept_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3312941BDC FOREIGN KEY (add_student_class_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE student_subject_year ADD CONSTRAINT FK_B096465BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_subject_year ADD CONSTRAINT FK_B096465BE5435205 FOREIGN KEY (subject_year_id) REFERENCES subject_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_year ADD CONSTRAINT FK_2948F7EC3DB272 FOREIGN KEY (subjectname_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE subject_year ADD CONSTRAINT FK_2948F7ECAE4D9375 FOREIGN KEY (steacher_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE subject_year_course ADD CONSTRAINT FK_96839EC4E5435205 FOREIGN KEY (subject_year_id) REFERENCES subject_year (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_year_course ADD CONSTRAINT FK_96839EC4591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649C8F54046 FOREIGN KEY (add_department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3312941BDC');
        $this->addSql('ALTER TABLE subject_year_course DROP FOREIGN KEY FK_96839EC4591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9A526685A');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33E4B27423');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649C8F54046');
        $this->addSql('ALTER TABLE result_subject_year DROP FOREIGN KEY FK_CF3D3F797A7B643');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113C0DC136F');
        $this->addSql('ALTER TABLE student_subject_year DROP FOREIGN KEY FK_B096465BCB944F1A');
        $this->addSql('ALTER TABLE subject_year DROP FOREIGN KEY FK_2948F7EC3DB272');
        $this->addSql('ALTER TABLE result_subject_year DROP FOREIGN KEY FK_CF3D3F79E5435205');
        $this->addSql('ALTER TABLE student_subject_year DROP FOREIGN KEY FK_B096465BE5435205');
        $this->addSql('ALTER TABLE subject_year_course DROP FOREIGN KEY FK_96839EC4E5435205');
        $this->addSql('ALTER TABLE subject_year DROP FOREIGN KEY FK_2948F7ECAE4D9375');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE result_subject_year');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_subject_year');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_year');
        $this->addSql('DROP TABLE subject_year_course');
        $this->addSql('DROP TABLE `user`');
    }
}
