<?php


namespace Ree\seichi\skil\background;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class Fortune
{
    public static function doFortune(Item $item): int
    {
        $level = self::isFortunr($item);
        if (!$level)
        {
            return 0;
        }else{
            $count = $level;
            $bool = false;
            while (!$bool)
            {
                $count--;
                $rand = mt_rand(1 ,100);
                $bool = self::getRandom($rand);
                if (round($level / 3) >= $count)
                {
                    $bool = true;
                }
            }

        }
        return $count;
    }

    private static function getRandom(int $int): bool
    {
        if ($int <= 33)
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param Item $item
     * @return bool|int
     */
    public static function isFortunr(Item $item)
    {
        $ench = $item->getEnchantmentLevel(Enchantment::FORTUNE);
        if ($ench)
        {
            return $ench;
        }else{
            return false;
        }
    }
}