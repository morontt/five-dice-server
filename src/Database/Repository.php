<?php

namespace FiveDice\Database;

use Doctrine\DBAL\Connection;
use FiveDice\Model\GameState;
use FiveDice\Model\Player;

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
            $this->db->insert('player', [
                'pl_name' => $name,
            ]);

            $dbResult = [
                'id' => $this->db->lastInsertId(),
                'pl_name' => $name,
            ];
        }

        $player = new Player($dbResult);

        return $player;
    }

    /**
     * @param GameState $gameState
     * @param int $playerId
     */
    public function createGame(GameState $gameState, $playerId)
    {
        $this->db->insert('game_state', [
            'hash' => $gameState->hash,
            'game_status' => $gameState->status,
            'players' => $playerId,
            'created_at' => $gameState->createdAt->toDateTimeString(),
        ]);

        $gameState->id = $this->db->lastInsertId();
    }

    /**
     * @param int $gameStateId
     * @param int $playerId
     */
    public function createPlayerScore($gameStateId, $playerId)
    {
        $this->db->insert('player_score', [
            'player_id' => $playerId,
            'game_state_id' => $gameStateId,
        ]);
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getPendingGames()
    {
        $stmt = $this->db->prepare('SELECT hash FROM game_state WHERE game_status = :game_status');
        $stmt->bindValue('game_status', GameState::STATUS_PENDING);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $hash
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getPendingGameStateWithPlayers($hash)
    {
        $sql = <<<SQL
SELECT
  gs.id,
  gs.players,
  gs.need_players,
  ps.player_id
FROM game_state AS gs
INNER JOIN player_score AS ps
  ON gs.id = ps.game_state_id
WHERE gs.hash = :hash
  AND gs.game_status = :game_status
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('hash', $hash);
        $stmt->bindValue('game_status', GameState::STATUS_PENDING);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $hash
     * @param string $players
     * @param bool $complete
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function joinToGame($hash, $players, $complete)
    {
        if ($complete) {
            return $this->db->executeUpdate(
                'UPDATE game_state SET players = ?, game_status = ? WHERE hash = ?',
                [$players, GameState::STATUS_COMPLETE_JOIN, $hash]
            );
        } else {
            return $this->db->executeUpdate(
                'UPDATE game_state SET players = ? WHERE hash = ?',
                [$players, $hash]
            );
        }
    }

    /**
     * @param $hash
     * @param Player $player
     * @return GameState|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getStateObject($hash, Player $player)
    {
        $sql = <<<SQL
SELECT * FROM game_state AS gs
INNER JOIN player_score AS ps
  ON gs.id = ps.game_state_id
WHERE gs.hash = :hash
  AND ps.player_id = :player_id
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('hash', $hash);
        $stmt->bindValue('player_id', $player->id);
        $stmt->execute();

        $dbResult = $stmt->fetch();
        if ($dbResult === false) {
            return null;
        }

        return (new GameState())->createFromArray($dbResult);
    }
}
