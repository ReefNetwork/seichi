<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\Gatya;

class ItemShopForm implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'form',
			'title' => 'ショップ',
			'content' => "",
			'buttons' => [
				[
					'text' => "スタンダードつるはし"
				],
				[
					'text' => "スタンダードしゃべる"
				],
				[
					'text' => "すごいつるはし"
				],
				[
					'text' => "すごいしゃべる"
				],
				[
					'text' => "かたいつるはし"
				],
				[
					'text' => "かた...ぁ\\い しゃべる"
				],
				[
					'text' => "ステーキ"
				],
				[
					'text' => "ガチャリンゴ(小)"
				],
				[
					'text' => "ガチャリンゴ(大)"
				],
				[
					'text' => "はなび"
				],
				[
					'text' => "戻る"
				],
			]
		];
	}

	public function handleResponse(Player $player, $data): void
	{
		if ($data === NULL) {
			return;
		}
		switch ($data) {
			case 0:
				$item = Item::get(Item::IRON_PICKAXE);
				$item->setCustomName("スタンダードつるはし");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 15));
				$player->sendForm(new BuyForm($item ,20));
				break;

			case 1:
				$item = Item::get(Item::IRON_SHOVEL);
				$item->setCustomName("スタンダードしゃべる");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 15));
				$player->sendForm(new BuyForm($item ,20));
				break;

			case 2:
				$item = Item::get(Item::DIAMOND_PICKAXE);
				$item->setCustomName("すごいつるはし");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 7));
				$player->sendForm(new BuyForm($item ,100));
				break;

			case 3:
				$item = Item::get(Item::DIAMOND_SHOVEL);
				$item->setCustomName("すごいしゃべる");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 7));
				$player->sendForm(new BuyForm($item ,100));
				break;

			case 4:
				$item = Item::get(Item::DIAMOND_PICKAXE);
				$item->setCustomName("かたいつるはし");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 40));
				$player->sendForm(new BuyForm($item ,500));
				break;

			case 5:
				$item = Item::get(Item::DIAMOND_SHOVEL);
				$item->setCustomName("かたいしゃべる");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 40));
				$player->sendForm(new BuyForm($item ,500));
				break;

			case 6:
				$player->sendForm(new BuyForm(Item::get(Item::STEAK) ,10));
				break;

			case 7:
				$player->sendForm(new BuyForm(Gatya::getGatya(Gatya::APPLE1) ,5));
				break;

			case 8:
				$player->sendForm(new BuyForm(Gatya::getGatya(Gatya::APPLE2) ,50));
				break;

			case 9:
				$player->sendForm(new BuyForm(Item::get(Item::FIREWORKS) ,10));
				break;

			case 10:
				$player->sendForm(new ShopForm());
				break;

			default:
				$player->sendMessage(ReefAPI::ERROR . 'ショップが見つかりませんでした');
		}
	}
}