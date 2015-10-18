<?php

namespace FiveDice\Database;

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
     * @param bool $dryRun
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function migrate($dryRun)
    {
        $sm = $this->db->getSchemaManager();

        $fromSchema = $sm->createSchema();
        $toSchema = $this->buildSchema();

        $queries = $fromSchema->getMigrateToSql($toSchema, $this->db->getDatabasePlatform());
        if (!$dryRun) {
            foreach ($queries as $query) {
                $this->db->exec($query);
            }
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
        $playersTable->addColumn('id', 'integer', ['autoincrement' => true,]);
        $playersTable->addColumn('pl_name', 'string', ['length' => 16]);
        $playersTable->addUniqueIndex(['pl_name']);
        $playersTable->setPrimaryKey(['id']);

        $gamesTable = $schema->createTable('game_state');
        $gamesTable->addColumn('id', 'integer', ['autoincrement' => true,]);
        $gamesTable->addColumn('hash', 'string', ['length' => 8]);
        $gamesTable->addColumn('game_status', 'integer');
        $gamesTable->addColumn('players', 'string', ['length' => 32]);
        $gamesTable->addColumn('step_player', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('count_rolling', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('dice_1', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('dice_2', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('dice_3', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('dice_4', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('dice_5', 'integer', ['notnull' => false,]);
        $gamesTable->addColumn('need_players', 'integer', ['default' => 2,]);
        $gamesTable->addColumn('created_at', 'datetime');
        $gamesTable->addUniqueIndex(['hash']);
        $gamesTable->setPrimaryKey(['id']);

        $playerScoreTable = $schema->createTable('player_score');
        $playerScoreTable->addColumn('player_id', 'integer');
        $playerScoreTable->addColumn('game_state_id', 'integer');
        $playerScoreTable->addColumn('pair', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('two_pairs', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('triplet', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('full_house', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('straight', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('big_straight', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('odd', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('even', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('quads', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('poker', 'integer', ['notnull' => false,]);
        $playerScoreTable->addColumn('sum', 'integer', ['notnull' => false,]);
        $playerScoreTable->setPrimaryKey(['player_id', 'game_state_id']);
        $playerScoreTable->addIndex(['player_id']);
        $playerScoreTable->addIndex(['game_state_id']);

        return $schema;
    }
}
