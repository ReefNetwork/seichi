<?php

namespace Ree\seichi\skil;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\seichi\PlayerTask;

class Ice_7_7_7 extends Skil
{
    /**
     * @return string
     */
    public static function getName()
    {
        return "Ice_7_7_7";
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return "Ice_7_7_7";
    }

	/**
	 * @param Block $block
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Player $p
	 * @return array
	 */
    public static function getSpace(Block $block,int $x ,int $y ,int $z ,Player $p)
    {
        $space = [];
        for ($x = -3 ;$x <= 3 ;$x++)
		{
			for ($y = -3 ;$y <= 3 ;$y++)
			{
				for ($z = -3 ;$z <= 3 ;$z++)
				{
					$bl = $block->add($x, $y, $z);
					$space[] = $bl->asVector3();
				}
			}
		}
        return $space;
    }

    /**
     * @return int
     */
    public static function getMana()
    {
        return 300;
    }

    /**
     * @return int
     */
    public static function getSkilpoint()
    {
        return 100;
    }

    /**
     * @return int
     */
    public static function getSlot()
    {
        return 21;
    }

    /**
     * @return Item
     */
    public static function getIcon()
    {
        $item = Item::get(Item::DIAMOND, 0, 1);

        return $item;
    }

	/**
	 * @return int
	 */
    public static function getCoolTime()
	{
		return 0;
	}

    public static function getNeedskil()
    {
    	return Skil_3_3_3::getClassName();
    }

    public static function isWalkSkil(): bool
	{
		return true;
	}

	/**
	 * @return null|Block
	 */
	public static function getTarget()
	{
		return Block::get(Block::WATER);
	}

	/**
	 * @return Block
	 */
	public static function getChangeBlock(): Block
	{
		return Block::get(Block::PACKED_ICE);
	}
}