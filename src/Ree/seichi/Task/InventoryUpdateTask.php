<?php


namespace Ree\seichi\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use Ree\reef\main;
use Ree\reef\ReefAPI;
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
        $p = $this->pT->getPlayer();
        if (!$p instanceof Player)
        {
            return;
        }
        if (!$p->isOnline())
        {
            return;
        }

        $this->pT->updateInventory();
        $this->pT->sendBar();
        ReefAPI::UpdateSyogo($this->pT->getPlayer() ,main::getMain()->getSyogo()->get($p->getName()));
    }
}