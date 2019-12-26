<?php


namespace Ree\seichi\skil;


use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\parkour\main;

class Skil_5_3_5 extends Skil
{

    public static function getName()
    {
        return "5_3_5";
    }

    public static function getClassName()
    {
        return "Skil_5_3_5";
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
                $mx = 4;
                $sz = -2;
                $mz = 2;
                break;
            case 1:
                $sx = -2;
                $mx = 2;
                $sz = 0;
                $mz = 4;
                break;
            case 2:
                $sx = -4;
                $mx = 0;
                $sz = -2;
                $mz = 2;
                break;
            case 3:
                $sx = -2;
                $mx = 2;
                $sz = -4;
                $mz = 0;
                break;
            default:
                \Ree\seichi\main::getpT($p->getName())->errer("line" . __LINE__ . " 不正な方角");
        }

        if ($p->getFloorY() > $y) {
            for ($x = -2; $x <= 2; $x++) {
                for ($y = -2; $y <= 0; $y++) {
                    for ($z = -2; $z <= 2; $z++) {
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
        return 30;
    }

    public static function getSkilpoint()
    {
        return 50;
    }

    public static function getSlot()
    {
        return 31;
    }

    public static function getIcon()
    {
        $item = Item::get(Item::IRON_ORE, 0, 1);

        return $item;
    }

    public static function getCoolTime()
	{
		return 14;
	}
}