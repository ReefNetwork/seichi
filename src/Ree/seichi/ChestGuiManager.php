<?php

/*
 * Copyright (c) 2019 tedo0627
 *
 *Permission is hereby granted, free of charge, to any person obtaining a copy
 *of this software and associated documentation files (the "Software"), to deal
 *in the Software without restriction, including without limitation the rights
 *to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *copies of the Software, and to permit persons to whom the Software is
 *furnished to do so, subject to the following conditions:
 *
 *The above copyright notice and this permission notice shall be included in all
 *copies or substantial portions of the Software.
 *
 *THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *SOFTWARE.
 *
 *
 * Copyright (c) 2019 ツキミヤ
 *
 *Permission is hereby granted, free of charge, to any person obtaining a copy
 *of this software and associated documentation files (the "Software"), to deal
 *in the Software without restriction, including without limitation the rights
 *to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *copies of the Software, and to permit persons to whom the Software is
 *furnished to do so, subject to the following conditions:

 *The above copyright notice and this permission notice shall be included in all
 *copies or substantial portions of the Software.

 *THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *SOFTWARE.
 */

namespace Ree\seichi;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\scheduler\Task;
use Ree\seichi\main;
use Ree\seichi\PlayerTask;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\tile\Chest;
use pocketmine\math\Vector3;
use pocketmine\inventory\DoubleChestInventory;

class ChestGuiManager
{

    /**
     * @param $pT
     * @param $title
     */
    static public function sendGui($pT, $title)
    {

        $p = $pT->getPlayer();
        $n = $p->getName();

        $x = (int)$p->x;
        $y = (int)$p->y + 4;
        $z = (int)$p->z;

        $vector = new Vector3($x, (int)$p->y + 1, $z);
        $p->getLevel()->setBlock($vector, Block::get(90, 0));
        main::getMain()->getScheduler()->scheduleDelayedTask(new CloseTask($p, $vector), 2);

        $pT->s_gui = [$x, $y, $z];

        $block1 = Block::get(Block::CHEST);
        $block1->setComponents($x, $y, $z);
        $p->level->sendBlocks([$p], [$block1]);

        $block2 = Block::get(Block::CHEST);
        $block2->setComponents($x + 1, $y, $z);
        $p->level->sendBlocks([$p], [$block2]);

        $nbt = Chest::createNBT($block1);
        $nbt->setString("CustomName", $n . "'s" . $title);
        $nbt->setInt("pairx", $x + 1);
        $nbt->setInt("pairz", $z);
        $nbt->setTag(new CompoundTag("s_chest",
            [
                new StringTag("name", $n),
                new StringTag("chest", $title)
            ]));
        $block1 = Chest::createTile(Chest::CHEST, $p->level, $nbt);

        $nbt = Chest::createNBT($block2);
        $nbt->setString("CustomName", $n . "'s" . $title);
        $nbt->setInt("pairx", $x);
        $nbt->setInt("pairz", $z);
        $nbt->setTag(new CompoundTag("s_chest",
            [
                new StringTag("name", $n),
                new StringTag("chest", $title)
            ]));
        $block2 = Chest::createTile(Chest::CHEST, $p->level, $nbt);

        $instance = new DoubleChestInventory($block1, $block2);

        main::getMain()->getScheduler()->scheduleDelayedTask(new ChestTask($p, $instance), 3);

        main::getMain()->getScheduler()->scheduleDelayedTask(new CheckTask($p, $instance), 3);

        switch ($title) {
            case "StackStrage":
                break;
        }
    }

    /**
     * @param $ev
     */
    static public function onClose($ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pT = main::getpT($n);

        $pT->s_open = false;

        if (isset($pT->s_gui)) {
            $x = $pT->s_gui[0];
            $y = $pT->s_gui[1];
            $z = $pT->s_gui[2];

            $p->level->sendBlocks([$p], [$p->level->getBlockAt($x, $y, $z)]);
            $p->level->sendBlocks([$p], [$p->level->getBlockAt($x + 1, $y, $z)]);
        }

        $pT->s_gui = NULL;
    }
}

class CloseTask extends Task
{
    public function __construct($p, $vector3)
    {
        $this->p = $p;
        $this->vector3 = $vector3;
    }

    public function onRun(int $currentTick)
    {
        $this->p->getLevel()->setBlock($this->vector3, Block::get(0, 0));
    }
}

class ChestTask extends Task
{
    public function __construct($p, $in)
    {
        $this->p = $p;
        $this->instance = $in;
    }

    public function onRun(int $currentTick)
    {
        $this->p->addWindow($this->instance);
    }
}

class CheckTask extends Task
{
    public function __construct()
    {

    }

    public function onRun(int $currentTick)
    {
        // TODO: Implement onRun() method.
    }
}