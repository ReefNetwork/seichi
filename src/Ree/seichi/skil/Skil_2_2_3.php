<?php


namespace Ree\seichi\skil;


use pocketmine\item\Item;

class Skil_2_2_3 extends Skil
{

    public function getName()
    {
        return "2_2_3";
    }

    public function getClassName()
    {
        return "Skil_2_2_3";
    }

    /**
     * @param \pocketmine\block\Block $block
     * @param int $x
     * @param int $y
     * @param int $z
     * @return array|\pocketmine\math\Vector3
     */
    public function getSpace($block, $x, $y, $z)
    {
        $x = 0;
        $z = 0;
        $space = [];
        $p = self::$p;
        $direction = $p->getDirection();
        switch ($direction)
        {
            case 0:
                $sx = 0;
                $mx = 1;
                $sz = -1;
                $mz = 1;
                break;
            case 1:
                $sx = -1;
                $mx = 1 ;
                $sz = 0;
                $mz = 1;
                break;
            case 2:
                $sx = -1;
                $mx = 0;
                $sz = -1;
                $mz = 1;
                break;
            case 3:
                $sx = -1;
                $mx = 1;
                $sz = -1;
                $mz = 0;
                break;
            default:
                $this->getpT()->errer("line" . __LINE__ . " 不正な方角", $this);
        }

        if ($p->getFloorY() == $y) {
            for ($x = $sx; $x <= $mx; $x++) {
                for ($y = 0; $y <= 1; $y++) {
                    for ($z = $sz; $z <= $mz; $z++) {
                        $bl = $block->add($x, $y, $z);
                        $space[] = $bl->asVector3();
                    }
                }
            }
            return $space;
        }

        for ($x = $sx; $x <= $mx; $x++) {
            for ($y = 0; $y >= -1; $y--) {
                for ($z = $sz; $z <= $mz; $z++) {
                    $bl = $block->add($x, $y, $z);
                    $space[] = $bl->asVector3();
                }
            }
        }
        return $space;
    }

    public function getMana()
    {
        return 5;
    }

    public static function getSkilpoint()
    {
        return 15;
    }

    public static function getSlot()
    {
        return 29;
    }

    public static function getIcon()
    {
        $item = Item::get(Item::LEAVES, 0, 1);

        return $item;
    }
}