<?php

namespace Ree\seichi\skil;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Ree\seichi\PlayerTask;

class Skil
{
    /**
     * @var string[]
     */
    public const SKILLIST = [
        "Skil",
        "Skil_1_2_1",
        "Skil_2_2_3",
        "Skil_3_3_3",
        "Skil_5_3_5",
    ];

    /**
     * @var PlayerTask
     */
    protected $pT = NULL;

    /**
     * @var Player
     */
    protected static $p;

    /**
     * Skil constructor.
     * @param PlayerTask $pT
     */
    public function __construct(PlayerTask $pT)
    {
        $this->pT = $pT;
        self::$p = $pT->getPlayer();
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return "Default";
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return "Skil";
    }

    /**
     * @param Block $block
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Player $p
     * @return array
     */
    public static function getSpace(Block $block ,int $x ,int $y ,int $z ,Player $p)
    {
        $space[]= $block->asVector3();
        return $space;
    }

    /**
     * @return int
     */
    public static function getMana()
    {
        return 0;
    }

    /**
     * @return int
     */
    public static function getSkilpoint()
    {
        return 0;
    }

    /**
     * @return int
     */
    public static function getSlot()
    {
        return 27;
    }

    /**
     * @return Item
     */
    public static function getIcon()
    {
        $item = Item::get(2, 0, 1);
        $item->setCustomName("スキル無し");

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

    }
}