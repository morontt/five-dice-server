<?php

namespace Database;

use Doctrine\DBAL\Connection;
use Model\GameState;
use Model\Player;

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
     * @return Player
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getPlayer($name)
    {
        $stmt = $this->db->prepare('SELECT * FROM player WHERE pl_name = :pl_name');
        $stmt->bindValue('pl_name', $name);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result)) {
            $dbResult = $result[0];
        } else {
            $id = $this->db->insert('player', [
                'pl_name' => $name,
            ]);

            $dbResult = [
                'id' => $id,
                'pl_name' => $name,
            ];
        }

        $player = new Player($dbResult);

        return $player;
    }

    /**
     * @param GameState $gameState
     */
    public function createGame(GameState $gameState)
    {
        $gameState->id = $this->db->insert('game_state', [
            'hash' => $gameState->hash,
            'game_status' => $gameState->status,
            'created_at' => $gameState->createdAt->toDateTimeString(),
        ]);
    }

    /**
     * @param GameState $gameState
     * @param Player $player
     */
    public function createPlayerScore(GameState $gameState, Player $player)
    {
        $this->db->insert('player_score', [
            'player_id' => $player->id,
            'game_state_id' => $gameState->id,
        ]);
    }
}
