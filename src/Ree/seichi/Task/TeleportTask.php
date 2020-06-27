<?php


namespace Ree\seichi\Task;


use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class TeleportTask extends Task
{
    /**
     * @var Player
     */
    private $p;
    /**
     * @var Position
     */
    private $pos;
    public function __construct($p ,$pos)
    {
        $this->p = $p;
        $this->pos = $pos;
    }

    public function onRun(int $currentTick)
    {
        $this->p->teleport($this->pos);
    }
}