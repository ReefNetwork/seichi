<?php

namespace Ree\seichi\skil\background;

use Ree\seichi\PlayerTask;

class BreakMana
{
    /**
     * @param PlayerTask $pT
     */
    public static function onBreak(PlayerTask $pT)
    {
        $level = $pT->s_level;
        if (mt_rand(1 ,10) <= 4)
        {
            return;
        }
        if ($level <= 10)
        {
            $mana = $level + 3;
        }else{
            $mana = ($level % 2);
            $mana += 10;
        }

        $max = $pT->getMaxmana() - $pT->s_mana;
        if ($mana > $max)
        {
            $pT->s_mana = $pT->getMaxmana();
            return;
        }

        $pT->s_mana += $mana;
    }
}