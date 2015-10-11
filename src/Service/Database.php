<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class Database
{
    protected $db;

    /**
     * @param Connection $db
     */
    function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $name
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getPlayer($name)
    {
        $stmt = $this->db->prepare('SELECT * FROM player WHERE pl_name = :pl_name');
        $stmt->bindValue('pl_name', $name);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result)) {
            $player = $result[0];
        } else {
            $id = $this->db->insert('player', [
                'pl_name' => $name,
            ]);

            $player = [
                'id' => $id,
                'pl_name' => $name,
            ];
        }

        return $player;
    }

    /**
     * @return array
     */
    public function schemaCreate()
    {
        $schema = new Schema();

        $playersTable = $schema->createTable('player');
        $playersTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $playersTable->addColumn('pl_name', 'string', ['length' => 16]);
        $playersTable->addUniqueIndex(['pl_name']);
        $playersTable->setPrimaryKey(['id']);

        $queries = $schema->toSql($this->db->getDatabasePlatform());
        foreach ($queries as $query) {
            $this->db->exec($query);
        }
    }
}
