<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class SeaShopForm implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'form',
			'title' => 'ショップ',
			'content' => "",
			'buttons' => [
				[
					'text' => "スポンジ"
				],
				[
					'text' => "シーランタン"
				],
				[
					'text' => "プリズマリン"
				],
				[
					'text' => "プリズマリンレンガ"
				],
				[
					'text' => "ダークプリズマリン"
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
				$player->sendForm(new BuyForm(Item::get(Item::SPONGE) ,100));
				break;

			case 1:
				$player->sendForm(new BuyForm(Item::get(Item::SEA_LANTERN) ,15));
				break;

			case 2:
				$player->sendForm(new BuyForm(Item::get(Item::PRISMARINE) ,5));
				break;

			case 3:
				$player->sendForm(new BuyForm(Item::get(Item::PRISMARINE ,1) ,5));
				break;

			case 4:
				$player->sendForm(new BuyForm(Item::get(Item::PRISMARINE ,2) ,5));
				break;

			case 5:
				$pT = main::getpT($player->getName());
				$player->sendForm(new MenuForm($pT));
				break;

			default:
				$player->sendMessage(ReefAPI::ERROR . 'ショップが見つかりませんでした');
		}
	}
}