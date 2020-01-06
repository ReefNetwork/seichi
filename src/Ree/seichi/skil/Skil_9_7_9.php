<?php


namespace Ree\seichi\skil;


use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Ree\seichi\main;

class Skil_9_7_9 extends Skil
{

    public static function getName()
    {
        return "9_7_9";
    }

    public static function getClassName()
    {
        return "Skil_9_7_9";
    }

    /**
     * @param Block $block
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Player $p
     * @return array|Vector3
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
                $mx = 8;
                $sz = -4;
                $mz = 4;
                break;
            case 1:
                $sx = -4;
                $mx = 4;
                $sz = 0;
                $mz = 8;
                break;
            case 2:
                $sx = -8;
                $mx = 0;
                $sz = -4;
                $mz = 4;
                break;
            case 3:
                $sx = -4;
                $mx = 4;
                $sz = -8;
                $mz = 0;
                break;
            default:
                main::getpT($p->getName())->errer("line" . __LINE__ . " 不正な方角");
                return [];
        }

        if ($p->getFloorY() > $y) {
            for ($x = -4; $x <= 4; $x++) {
                for ($y = -6; $y <= 0; $y++) {
                    for ($z = -4; $z <= 4; $z++) {
                        $bl = $block->add($x, $y, $z);
                        $space[] = $bl->asVector3();
                    }
                }
            }
            return $space;
        }

		if ($p->getFloorY() + 6 < $y) {
			for ($x = $sx; $x <= $mx; $x++) {
				for ($y = -3; $y <= 3; $y++) {
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
            for ($y = $sy; $y <= 6; $y++) {
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
        return 100;
    }

    public static function getSkilpoint()
    {
        return 100;
    }

    public static function getSlot()
    {
        return 32;
    }

    public static function getIcon()
    {
        $item = Item::get(Item::DIAMOND_BLOCK, 0, 1);

        return $item;
    }

    public static function getCoolTime()
	{
		return 50;
	}

	public static function getNeedskil()
	{
		return Skil_7_5_7::getClassName();
	}
}