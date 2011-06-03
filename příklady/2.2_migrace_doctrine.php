<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20100416130401 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('users');
        $table->addColumn('username', 'string');
        $table->addColumn('password', 'string');
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('users');
    }
}