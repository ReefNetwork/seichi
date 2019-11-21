<?php


namespace Ree\seichi\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;

class ClearTask extends Task
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
        // TODO: Implement onRun() method.
        $item = $this->p->getCursorInventory()->getItem(0);
    }
}