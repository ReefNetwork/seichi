<?php


namespace Ree\seichi\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use Ree\seichi\PlayerTask;
use Ree\StackStrage\Virchal\WorldSelect;

class InventoryUpdateTask extends Task
{
    /**
     * @var PlayerTask
     */
    private $pT;

    public function __construct(PlayerTask $pT)
    {
        $this->pT = $pT;
    }

    public function onRun(int $currentTick)
    {
        $this->pT->updateInventory();

        $p = $this->pT->getPlayer();
        $level = $p->getLevel();
        if ($level->getName() === "lobby") {
            if (264 <= $p->getFloorX() and $p->getFloorX() <= 265)
            {
                if (255 <= $p->getFloorZ() and $p->getFloorZ() <= 256)
                {
                    $this->pT->s_chestInstance = new WorldSelect($this->pT ,false);
                }
            }
        }
    }
}