<?php


namespace Ree\seichi\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use Ree\seichi\main;

class ImmobileTask extends Task
{
    /**
     * @var Player
     */
    private $p;
    public function __construct(Player $p)
    {
        $this->p = $p;
    }

    public function onRun(int $currentTick)
    {
        $this->p->setImmobile(false);
        main::getpT($this->p->getName())->sendLog("ImmobileTask>>Immobileを解除しました");
    }
}