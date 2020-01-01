<?php


namespace Ree\seichi\form;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;
use Ree\seichi\PlayerTask;

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
					'text' => "ツール"
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
			case 1:
				$player->sendMessage(ReefAPI::BAD.'未実装です');
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