<?php

namespace Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class Migrator
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function migrate()
    {
        $sm = $this->db->getSchemaManager();

        $fromSchema = $sm->createSchema();
        $toSchema = $this->buildSchema();

        $queries = $fromSchema->getMigrateToSql($toSchema, $this->db->getDatabasePlatform());
        foreach ($queries as $query) {
            $this->db->exec($query);
        }

        return $queries;
    }

    /**
     * @return Schema
     */
    protected function buildSchema()
    {
        $schema = new Schema();

        $playersTable = $schema->createTable('player');
        $playersTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $playersTable->addColumn('pl_name', 'string', ['length' => 16]);
        $playersTable->addUniqueIndex(['pl_name']);
        $playersTable->setPrimaryKey(['id']);

        return $schema;
    }
}
