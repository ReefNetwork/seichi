<?php


namespace Ree\seichi\Task;


use pocketmine\scheduler\Task;
use Ree\seichi\PlayerTask;

class ScoreBoardTask extends Task
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
        $this->pT->sendScore();
    }
}