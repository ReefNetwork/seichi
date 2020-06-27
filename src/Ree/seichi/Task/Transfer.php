<?php


namespace Ree\seichi\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use Ree\doumei\TransferForm;

class Transfer extends Task
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
        $this->p->sendForm(new TransferForm());
    }
}