<?php


namespace Ree\seichi\skil;




use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;

class Skil_1_2_1 extends Skil
{

    public static function getName()
    {
        return "1_2_1";
    }

    public static function getClassName()
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
    public static function getSpace(Block $block,int $x,int $y,int $z ,Player $p)
    {
        $x = 0;
        $z = 0;
        $space = [];

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

    public static function getMana()
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