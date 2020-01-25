<?php


namespace Ree\seichi\form;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;
use Ree\seichi\PlayerTask;

class EffectShop implements Form
{
	public function jsonSerialize()
	{
		return [
			'type' => 'custom_form',
			'title' => 'エフェクトショップ',
			'content' => [
				[
					"type" => "dropdown",
					"text" => "エフェクトショップ",
					"options" => [
						'暗視(Lv1) 5coin/分',
						'跳躍力上昇(Lv2) 5coin/分',
						'跳躍力上昇(Lv5) 10coin/分',
						'採掘速度上昇(Lv2) 10coin/分',
						'スピード上昇(Lv2) 10coin/分',
						'水中呼吸(Lv1) 10coin/分',
					],
				],
				[
					"type" => "input",
					"text" => "時間を入力してください(分)",
					"placeholder" => "int",
					"default" => "",
				],
			]
		];
	}

	public function handleResponse(Player $player, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if (isset ($data[1])) {
			if (!is_numeric($data[1])) {
				$player->sendMessage(ReefAPI::BAD . '数値を入力してください');
				return;
			}
			if ($data[1] <= 0)
			{
				$player->sendMessage(ReefAPI::BAD . '数値を入力してください');
				return;
			}
			$pT = main::getpT($player->getName());
			switch ($data[0]) {
				case 0:
					if ($this->removeCoin($pT, 5 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), $data[1] * 1200, 0));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				case 1:
					if ($this->removeCoin($pT, 5 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), $data[1] * 1200, 1));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				case 2:
					if ($this->removeCoin($pT, 10 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), $data[1] * 1200, 4));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				case 3:
					if ($this->removeCoin($pT, 10 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), $data[1] * 1200, 1));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				case 4:
					if ($this->removeCoin($pT, 10 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), $data[1] * 1200, 1));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				case 5:
					if ($this->removeCoin($pT, 10 * $data[1])) {
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::WATER_BREATHING), $data[1] * 1200, 1));
						$player->sendMessage(ReefAPI::GOOD.'購入しました');
					} else {
						$player->sendMessage(ReefAPI::BAD . 'お金が足りません');
					}
					break;

				default:
					$player->sendMessage(ReefAPI::ERROR . 'エフェクトが見つかりませんでした');
			}
		}
	}

	private function removeCoin(PlayerTask $pT, int $coin): bool
	{
		$pcoin = $pT->s_coin;
		if ($pcoin >= $coin) {
			$pT->s_coin = $pcoin - $coin;
			return true;
		} else {
			return false;
		}
	}
}