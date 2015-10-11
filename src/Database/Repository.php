<?php

namespace Database;

use Doctrine\DBAL\Connection;

class Repository
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
}
