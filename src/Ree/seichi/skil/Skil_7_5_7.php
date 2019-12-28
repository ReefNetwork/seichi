<?php


namespace Ree\seichi\skil;


use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\parkour\main;

class Skil_7_5_7 extends Skil
{

    public static function getName()
    {
        return "7_5_7";
    }

    public static function getClassName()
    {
        return "Skil_7_5_7";
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
                $mx = 6;
                $sz = -3;
                $mz = 3;
                break;
            case 1:
                $sx = -3;
                $mx = 3;
                $sz = 0;
                $mz = 6;
                break;
            case 2:
                $sx = -6;
                $mx = 0;
                $sz = -3;
                $mz = 3;
                break;
            case 3:
                $sx = -3;
                $mx = 3;
                $sz = -6;
                $mz = 0;
                break;
            default:
                \Ree\seichi\main::getpT($p->getName())->errer("line" . __LINE__ . " 不正な方角");
                return [];
        }

        if ($p->getFloorY() > $y) {
            for ($x = -3; $x <= 3; $x++) {
                for ($y = -4; $y <= 0; $y++) {
                    for ($z = -3; $z <= 3; $z++) {
                        $bl = $block->add($x, $y, $z);
                        $space[] = $bl->asVector3();
                    }
                }
            }
            return $space;
        }

		if ($p->getFloorY() == $y) {
			for ($x = $sx; $x <= $mx; $x++) {
				for ($y = 0; $y <= 4; $y++) {
					for ($z = $sz; $z <= $mz; $z++) {
						$bl = $block->add($x, $y, $z);
						$space[] = $bl->asVector3();
					}
				}
			}
			return $space;
		}

		$sy = $y - $p->getFloorY();
		$sy = $sy * -1;

        $i = 0;
        for ($x = $sx; $x <= $mx; $x++) {
            for ($y = $sy; $y <= 4; $y++) {
                for ($z = $sz; $z <= $mz; $z++) {
                    $bl = $block->add($x, $y, $z);
                    $space[] = $bl->asVector3();
                    $i++;
                }
            }
        }
        return $space;
    }

    public static function getMana()
    {
        return 70;
    }

    public static function getSkilpoint()
    {
        return 70;
    }

    public static function getSlot()
    {
        return 31;
    }

    public static function getIcon()
    {
        $item = Item::get(Item::DIAMOND_BLOCK, 0, 1);

        return $item;
    }

    public static function getCoolTime()
	{
		return 30;
	}

	public static function getNeedskil()
	{
		return Skil_5_3_5::getClassName();
	}
}