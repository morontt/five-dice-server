<?php

namespace Model;

class Player
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;


    /**
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->name = $data['pl_name'];
    }
}
