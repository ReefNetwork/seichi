<?php


namespace Ree\seichi;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use bboyyu51\pmdiscord\structure\Embeds;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use Ree\reef\ReefAPI;
use Ree\StackStrage\StackStrage_API;

class Gatya
{
    const GATYA = "s_gatya";
    const APPLE1 = "99991";
    const APPLE2 = "99992";
    const APPLE3 = "99993";
    const XP = "99999";

    const ENCHANT_ADD_MANA = 100;

    /**
     * @param Player $p
     * @return bool
     */
    public static function onGatya(Player $p): bool
    {
        $rand = mt_rand(1, 1000);
        if ($rand == 1)
        {
            $rand = mt_rand(1 ,5);
        }
        if ($rand == 5)
        {
            $rand = mt_rand(1 ,10);
        }
        $item = self::getGatya($rand ,$p);
        if (!$p->getInventory()->canAddItem($item)) {
            return false;
        }
        switch ($rand) {
            case $rand > 0 && $rand < 5:
                Server::getInstance()->broadcastMessage("§b" . $p->getName() . "§rさんが§aReef§eTool§rを引きました");
                $webhook = ReefAPI::getWebhook();
                $content = new Content();
                $content->setText("§b" . $p->getName() . "§rさんが§aReef§eTool§rを引きました");
                $webhook->add($content);
                $embeds = new Embeds();
                $webhook->add($embeds);
                $webhook->setCustomName("Gatya");
                Sender::send($webhook);
                $p->sendMessage("§e超大あたり");
                break;

            case 5:
                Server::getInstance()->broadcastMessage("§b" . $p->getName() . "§rさんが§aReef§eTool§rを引きましたwww");
                $webhook = ReefAPI::getWebhook();
                $content = new Content();
                $content->setText("§b" . $p->getName() . "§rさんが§aReef§eTool§rを引きましたwww");
                $webhook->add($content);
                $embeds = new Embeds();
                $webhook->add($embeds);
                $webhook->setCustomName("Gatya");
                Sender::send($webhook);
                $p->sendMessage("§e大あたり");
                break;

            case $rand > 5 && $rand < 10:
                Server::getInstance()->broadcastMessage("§b" . $p->getName() . "§rさんが§aReef§eArmor§rを引きました");
                $webhook = ReefAPI::getWebhook();
                $content = new Content();
                $content->setText("§b" . $p->getName() . "§rさんが§aReef§eArmor§rを引きました");
                $webhook->add($content);
                $embeds = new Embeds();
                $webhook->add($embeds);
                $webhook->setCustomName("Gatya");
                Sender::send($webhook);
                $p->sendMessage("§e超大あたり");
                break;

            case $rand > 10 && $rand < 170:
                $p->sendMessage("§eあたり");
                break;

            default:
                $p->sendMessage("§8はずれ");
        }
        $p->getInventory()->addItem($item);

        return true;
    }

    /**
     * @param Player $p
     * @param int $rand
     * @return bool|Item
     */
    public static function getGatya(int $rand ,Player $p = NULL)
    {
        $n = "true";
        if ($p)
        {
            $n = $p->getName();
        }
        switch ($rand) {
            case 0:
                $item = Item::get(Item::EMERALD, 3, 1);
                $item->setCustomName("ガチャ券\n\n所有者:" . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 0);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                break;

            case 1:
                $item = Item::get(Item::DIAMOND_PICKAXE);
                $item->setCustomName("§aReef§ePickaxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 1);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                $item->setUnbreakable();
                break;

            case 2:
                $item = Item::get(Item::DIAMOND_AXE);
                $item->setCustomName("§aReef§eAxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 2);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                $item->setUnbreakable();
                break;

            case 3:
                $item = Item::get(Item::DIAMOND_SHOVEL);
                $item->setCustomName("§aReef§eShovel\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 3);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                $item->setUnbreakable();
                break;

            case 4:
                $item = Item::get(Item::DIAMOND_SWORD);
                $item->setCustomName("§aReef§eSword\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 4);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::FIRE_ASPECT), 10));
                $item->setUnbreakable();
                break;

            case 5:
                $item = Item::get(Item::DIAMOND_HOE);
                $item->setCustomName("§aReef§eHoe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 5);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 10));
                $item->setUnbreakable();
                break;

            case 6:
                $item = Item::get(Item::DIAMOND_HELMET);
                $item->setCustomName("§aReef§eHelmet\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 6);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 10));
                $item->setUnbreakable();
                break;

            case 7:
                $item = Item::get(Item::ELYTRA);
                $item->setCustomName("§aReef§eElytra\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 7);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::THORNS), 10));
                break;

            case 8:
                $item = Item::get(Item::DIAMOND_LEGGINGS);
                $item->setCustomName("§aReef§eLeggings\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 8);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::THORNS), 10));
                $item->setUnbreakable();
                break;

            case 9:
                $item = Item::get(Item::DIAMOND_BOOTS);
                $item->setCustomName("§aReef§eBoots\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 9);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::THORNS), 10));
                $item->setUnbreakable();
                break;

            case 10:
                $item = Item::get(Item::DIAMOND_SWORD);
                $item->setCustomName("§aReef§eSword\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 4);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::FIRE_ASPECT), 10));
                break;

            case $rand > 10 && $rand < 30:
                $item = Item::get(Item::DIAMOND_PICKAXE);
                $item->setCustomName("§dSllk§ePickaxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 11);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                break;

            case $rand > 30 && $rand < 50:
                $item = Item::get(Item::DIAMOND_SHOVEL);
                $item->setCustomName("§dSllk§eShovel\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 31);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                break;

            case $rand > 50 && $rand < 70:
                $item = Item::get(Item::DIAMOND_AXE);
                $item->setCustomName("§dSllk§eAxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 51);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                break;

            case $rand > 70 && $rand < 120:
                $item = Item::get(Item::DIAMOND_PICKAXE);
                $item->setCustomName("§bSimple§ePickaxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 71);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));
                break;

            case $rand > 120 && $rand < 170:
                $item = Item::get(Item::DIAMOND_SHOVEL);
                $item->setCustomName("§bSimple§eShovel\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 121);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));
                break;

            case $rand > 170 && $rand < 200:
                $item = Item::get(Item::IRON_PICKAXE ,211 ,1);
                $item->setCustomName("§eLucky§ePickaxe\n\n§2所有者 : " . $n);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, 171);
                $nbt->setString(StackStrage_API::PLAYERNAME, $n);
                $item->setNamedTag($nbt);
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::FORTUNE), 10));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Gatya::ENCHANT_ADD_MANA), 10));
                break;

            case self::APPLE1:
                $item = Item::get(Item::GOLDEN_APPLE, 0, 1);
                $item->setCustomName("§eガチャリンゴ§r(§b小§r)\n食べると回復するよ!!!");
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 0, 1);
                $nbt->setInt(gatya::GATYA, self::APPLE1);
                $nbt->setString(StackStrage_API::PLAYERNAME, "NULL");
                $item->setNamedTag($nbt);
                break;

            case self::XP:
                $item = Item::get(Item::EXPERIENCE_BOTTLE  ,0 , 1);
                $nbt = $item->getNamedTag();
                $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                $nbt->setInt(gatya::GATYA, self::XP);
                $nbt->setString(StackStrage_API::PLAYERNAME, "NULL");
                $item->setNamedTag($nbt);
                break;

            default:
                $rand = mt_rand(1, 3);
                switch ($rand) {
                    case 1:
                        $count = mt_rand(1 ,3);
                        $item = Item::get(Item::GOLDEN_APPLE, 0, $count);
                        $item->setCustomName("§eガチャリンゴ§r(§b小§r)\n食べると回復するよ!!!");
                        $nbt = $item->getNamedTag();
                        $nbt->setInt(StackStrage_API::NOTSTACK, 0, 1);
                        $nbt->setInt(gatya::GATYA, self::APPLE1);
                        $nbt->setString(StackStrage_API::PLAYERNAME, "NULL");
                        $item->setNamedTag($nbt);
                        break;
                    case 2:
                        $count = mt_rand(1 ,8);
                        $item = Item::get(Item::GOLDEN_APPLE, 0, $count);
                        $item->setCustomName("§eガチャリンゴ§r(§b小§r)\n食べると回復するよ!!!");
                        $nbt = $item->getNamedTag();
                        $nbt->setInt(StackStrage_API::NOTSTACK, 0, 1);
                        $nbt->setInt(gatya::GATYA, self::APPLE1);
                        $nbt->setString(StackStrage_API::PLAYERNAME, "NULL");
                        $item->setNamedTag($nbt);
                        break;
                    case 3:
                        $count = mt_rand(1 ,16);
                        $item = Item::get(Item::EXPERIENCE_BOTTLE  ,0 , $count);
                        $nbt = $item->getNamedTag();
                        $nbt->setInt(StackStrage_API::NOTSTACK, 1);
                        $nbt->setInt(gatya::GATYA, self::XP);
                        $nbt->setString(StackStrage_API::PLAYERNAME, "NULL");
                        $item->setNamedTag($nbt);
                        break;
                }
        }
        return $item;
    }
}