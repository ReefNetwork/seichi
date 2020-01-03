<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;

class BlockShopForm implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'form',
			'title' => 'ショップ',
			'content' => "",
			'buttons' => [
				[
					'text' => "ネザー系"
				],
				[
					'text' => "海底神殿系"
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
				$player->sendForm(new NetherShopForm());
				break;

			case 1:
				$player->sendMessage(ReefAPI::BAD.'未実装です');
				break;

			case 2:
				$player->sendForm(new ShopForm());
				break;

			default:
				$player->sendMessage(ReefAPI::ERROR . 'ショップが見つかりませんでした');
		}
	}
}