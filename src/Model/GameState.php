<?php

namespace FiveDice\Model;

use Carbon\Carbon;
use Finite\StatefulInterface;
use FiveDice\Finite\StateMachine;

class GameState implements StatefulInterface
{
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_CLOSED = 3;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var int
     */
    public $status;

    /**
     * @var Carbon
     */
    public $createdAt;

    /**
     * @var array
     */
    public $players;

    /**
     * @var int
     */
    public $stepPlayer;

    /**
     * @var int
     */
    public $countRolling;

    /**
     * @var int
     */
    public $dice1;

    /**
     * @var int
     */
    public $dice2;

    /**
     * @var int
     */
    public $dice3;

    /**
     * @var int
     */
    public $dice4;

    /**
     * @var int
     */
    public $dice5;


    public function __construct()
    {
        $this->hash = substr(base_convert(md5(str_shuffle(microtime(true))), 16, 36), 6, 8);
        $this->status = self::STATUS_PENDING;
        $this->createdAt = new Carbon('now');
    }

    /**
     * @param array $data
     * @return GameState
     */
    public function createFromArray(array $data)
    {
        $this->id = (int)$data['id'];
        $this->hash = $data['hash'];
        $this->status = (int)$data['game_status'];

        $this->players = array_map(
            function ($e) {
                return (int)$e;
            },
            explode(':', $data['players'])
        );

        $i = function ($x) {
            if ($x !== null) {
                $x = (int)$x;
            }

            return $x;
        };

        $this->stepPlayer = $i($data['step_player']);
        $this->countRolling = $i($data['count_rolling']);
        $this->dice1 = $i($data['dice_1']);
        $this->dice2 = $i($data['dice_2']);
        $this->dice3 = $i($data['dice_3']);
        $this->dice4 = $i($data['dice_4']);
        $this->dice5 = $i($data['dice_5']);

        $this->createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $data['created_at']);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFiniteState()
    {
        return StateMachine::STATE_PENDING;
    }

    /**
     * @inheritdoc
     */
    public function setFiniteState($state)
    {
    }
}
