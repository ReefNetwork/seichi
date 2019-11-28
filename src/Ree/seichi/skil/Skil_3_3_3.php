<?php


namespace Ree\seichi\skil;


use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\parkour\main;

class Skil_3_3_3 extends Skil
{

    public static function getName()
    {
        return "3_3_3";
    }

    public static function getClassName()
    {
        return "Skil_3_3_3";
    }

    /**
     * @param \pocketmine\block\Block $block
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Player $p
     * @return array|\pocketmine\math\Vector3
     */
    public static function getSpace(Block $block,int $x,int $y,int $z ,Player $p)
    {
        $x = 0;
        $z = 0;
        $space = [];
        $direction = $p->getDirection();
        switch ($direction)
        {
            case 0:
                $sx = 0;
                $mx = 2;
                $sz = -1;
                $mz = 1;
                break;
            case 1:
                $sx = -1;
                $mx = 1;
                $sz = 0;
                $mz = 2;
                break;
            case 2:
                $sx = -2;
                $mx = 0;
                $sz = -1;
                $mz = 1;
                break;
            case 3:
                $sx = -1;
                $mx = 1;
                $sz = -2;
                $mz = 0;
                break;
            default:
                \Ree\seichi\main::getpT($p->getName())->errer("line" . __LINE__ . " 不正な方角");
        }

        if ($p->getFloorY() > $y) {
            for ($x = -1; $x <= 1; $x++) {
                for ($y = -2; $y <= 0; $y++) {
                    for ($z = -1; $z <= 1; $z++) {
                        $bl = $block->add($x, $y, $z);
                        $space[] = $bl->asVector3();
                    }
                }
            }
            return $space;
        }
        if ($p->getFloorY() == $y) {
            for ($x = $sx; $x <= $mx; $x++) {
                for ($y = 0; $y <= 2; $y++) {
                    for ($z = $sz; $z <= $mz; $z++) {
                        $bl = $block->add($x, $y, $z);
                        $space[] = $bl->asVector3();
                    }
                }
            }
            return $space;
        }
        for ($x = $sx; $x <= $mx; $x++) {
            for ($y = 1; $y >= -1; $y--) {
                for ($z = $sz; $z <= $mz; $z++) {
                    $bl = $block->add($x, $y, $z);
                    $space[] = $bl->asVector3();
                }
            }
        }
        return $space;
    }

    public static function getMana()
    {
        return 12;
    }

    public static function getSkilpoint()
    {
        return 30;
    }

    public static function getSlot()
    {
        return 30;
    }

    public static function getIcon()
    {
        $item = Item::get(Item::TNT, 0, 1);

        return $item;
    }
}