<?php

namespace Ree\seichi\skil;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Ree\seichi\PlayerTask;

class Skil2_2_3
{
    /**
     * @var string[]
     */
    public const SKILLIST = [
        "Skil",
        "Skil_1_2_1",
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
    public function getName()
    {
        return "Default";
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return "Skil";
    }

    /**
     * @param Block $block
     * @param $x
     * @param $y
     * @param $z
     * @return Vector3 array
     */
    public function getSpace($block ,$x ,$y ,$z)
    {
        $space[]= $block->asVector3();
        return $space;
    }

    /**
     * @return int
     */
    public function getMana()
    {
        return 0;
    }

    /**
     * @return PlayerTask
     */
    public function getpT()
    {
        return $this->pT;
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
     * @param Vector3 $vector3
     */
    private function getDirection($vector3)
    {
        return;
    }
}