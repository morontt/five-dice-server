<?php

namespace FiveDice\Model;

use Carbon\Carbon;
use Finite\StatefulInterface;

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


    public function __construct()
    {
        $this->hash = substr(base_convert(md5(microtime(true)), 16, 36), 0, 8);
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
        $this->createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $data['created_at']);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFiniteState()
    {
    }

    /**
     * @inheritdoc
     */
    public function setFiniteState($state)
    {
    }
}
