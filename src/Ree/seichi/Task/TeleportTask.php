<?php


namespace Ree\seichi\Task;


use pocketmine\scheduler\Task;

class TeleportTask extends Task
{

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