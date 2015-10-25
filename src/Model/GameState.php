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
    const STATUS_COMPLETE_JOIN = 4;

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

    /**
     * @var int
     */
    public $pair;

    /**
     * @var int
     */
    public $twoPairs;

    /**
     * @var int
     */
    public $triplet;

    /**
     * @var int
     */
    public $fullHouse;

    /**
     * @var int
     */
    public $straight;

    /**
     * @var int
     */
    public $bigStraight;

    /**
     * @var int
     */
    public $odd;

    /**
     * @var int
     */
    public $even;

    /**
     * @var int
     */
    public $quads;

    /**
     * @var int
     */
    public $poker;

    /**
     * @var int
     */
    public $sum;

    /**
     * @var int
     */
    public $needPlayers;

    /**
     * @var string
     */
    protected $finiteState;


    public function __construct()
    {
        $this->hash = substr(base_convert(md5(str_shuffle(microtime(true))), 16, 36), 6, 8);
        $this->status = self::STATUS_PENDING;
        $this->createdAt = new Carbon('now');

        $this->finiteState = StateMachine::STATE_PENDING;
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
        $this->needPlayers = $i($data['need_players']);
        $this->dice1 = $i($data['dice_1']);
        $this->dice2 = $i($data['dice_2']);
        $this->dice3 = $i($data['dice_3']);
        $this->dice4 = $i($data['dice_4']);
        $this->dice5 = $i($data['dice_5']);

        $this->pair = $i($data['pair']);
        $this->twoPairs = $i($data['two_pairs']);
        $this->triplet = $i($data['triplet']);
        $this->fullHouse = $i($data['full_house']);
        $this->straight = $i($data['straight']);
        $this->bigStraight = $i($data['big_straight']);
        $this->odd = $i($data['odd']);
        $this->even = $i($data['even']);
        $this->quads = $i($data['quads']);
        $this->poker = $i($data['poker']);
        $this->sum = $i($data['sum']);

        $this->createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $data['created_at']);

        return $this;
    }

    /**
     * @return array
     */
    public function getDicesArray()
    {
        return [
            $this->dice1,
            $this->dice2,
            $this->dice3,
            $this->dice4,
            $this->dice5,
        ];
    }

    /**
     * @return array
     */
    public function getScoreArray()
    {
        return [
            'pair' => $this->pair,
            'two_pairs' => $this->twoPairs,
            'triplet' => $this->triplet,
            'full_house' => $this->fullHouse,
            'straight' => $this->straight,
            'big_straight' => $this->bigStraight,
            'odd' => $this->odd,
            'even' => $this->even,
            'quads' => $this->quads,
            'poker' => $this->poker,
            'sum' => $this->sum,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFiniteState()
    {
        return $this->finiteState;
    }

    /**
     * @inheritdoc
     */
    public function setFiniteState($state)
    {
        $this->finiteState = $state;
    }
}
