<?php


namespace Ree\seichi\skil;




use pocketmine\item\Item;

class Skil_1_2_1 extends Skil
{

    public function getName()
    {
        return "1_2_1";
    }

    public function getClassName()
    {
        return "Skil_1_2_1";
    }

    /**
     * @param \pocketmine\block\Block $block
     * @param int $x
     * @param int $y
     * @param int $z
     * @return array|\pocketmine\math\Vector3
     */
    public function getSpace($block ,$x ,$y ,$z)
    {
        $x = 0;
        $z = 0;
        $space = [];

        $p = self::$p;
        if ($p->getFloorY() == $y)
        {
            for ($y = 0 ;$y <= 1 ;$y++)
            {
                $bl = $block->add($x, $y, $z);
                $space[] = $bl->asVector3();
            }
            return $space;
        }

        for ($y = 0 ;$y >= -1 ;$y--)
        {
            $bl = $block->add($x, $y, $z);
            $space[] = $bl->asVector3();
        }
        return $space;
    }

    public function getMana()
    {
        return 1;
    }

    public static function getSkilpoint()
    {
        return 5;
    }

    public static function getSlot()
    {
        return 28;
    }

    public static function getIcon()
    {
        $item = Item::get(280, 0, 1);

        return $item;
    }
}