<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class OceanBlockForm implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'form',
			'title' => 'ショップ',
			'content' => "",
			'buttons' => [
				[
					'text' => ""
				],
				[
					'text' => ""
				],
				[
					'text' => ""
				],
				[
					'text' => ""
				],
				[
					'text' => ""
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
				$player->sendForm(new BuyForm(Item::get(87) ,1));
				break;

			case 1:
				$player->sendForm(new BuyForm(Item::get(Item::QUARTZ_BLOCK) ,5));
				break;

			case 2:
				$player->sendForm(new BuyForm(Item::get(Item::QUARTZ_STAIRS) ,5));
				break;

			case 3:
				$player->sendForm(new BuyForm(Item::get(Item::MAGMA) ,5));
				break;

			case 4:
				$player->sendForm(new BuyForm(Item::get(Item::GLOWSTONE) ,10));
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