<?php


namespace Ree\seichi\Task;


use pocketmine\scheduler\Task;
use Ree\reef\ReefAPI;
use Ree\seichi\PlayerTask;

class CoolTimeTask extends Task
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
        $this->pT->s_coolTime = 0;
        $this->pT->getPlayer()->sendTip(ReefAPI::GOOD.'クールタイムが終了しました');
    }
}