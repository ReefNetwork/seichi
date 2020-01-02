<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class ShopForm implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'form',
			'title' => 'ショップ',
			'content' => "",
			'buttons' => [
				[
					'text' => "ブロック"
				],
				[
					'text' => "アイテム"
				],
				[
					'text' => "エフェクト"
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
				$player->sendForm(new BlockShopForm());
				break;

			case 1:
				$player->sendForm(new ItemShopForm());
				break;

			case 2:
				$player->sendForm(new EffectShop());
				break;

			case 3:
				$pT = main::getpT($player->getName());
				$player->sendForm(new MenuForm($pT));
				break;

			default:
				$player->sendMessage(ReefAPI::ERROR . 'ショップが見つかりませんでした');
		}
	}
}