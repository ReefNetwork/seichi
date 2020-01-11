<?php


namespace Ree\seichi\Task;


use pocketmine\scheduler\Task;
use Ree\seichi\PlayerTask;

class FlyTask extends Task
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
        // TODO: Implement onRun() method.
        $bool = $this->pT->s_fly;
        if ($bool)
        {
            if ($this->pT->s_coin > 3)
            {
                $this->pT->s_coin = $this->pT->s_coin - 3;
                $this->pT->getPlayer()->sendMessage("§aフライは有効です");
            }else{
                $this->pT->s_fly = false;
                $this->pT->getPlayer()->sendMessage("§cお金が足りないためフライを停止します");
                $this->pT->getPlayer()->setAllowFlight(false);
                $this->pT->getPlayer()->setFlying(false);
            }
        }
    }
}