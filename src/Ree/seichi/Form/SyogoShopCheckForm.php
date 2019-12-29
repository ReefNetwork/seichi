<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class SyogoShopCheckForm implements Form
{
	/**
	 * @var string
	 */
	private $syogo;
	/**
	 * @var int
	 */
	private $money;
	public function __construct(string $syogo ,int $money)
	{
		$this->syogo = $syogo;
		$this->money = $money;
	}

	public function jsonSerialize()
	{
		$string = '称号 : '.$this->syogo."\n".'coin : '.$this->money;
		return [
			'type' => 'modal',
			'title' => '称号shop',
			'content' => "$string",
			"button1" => "称号を買う",
			"button2" => "やめる",
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if ($data)
		{
			$pT = main::getpT($p->getName());
			if ($pT->s_coin >= $this->money) {
				$pT->s_coin= $pT->s_coin - $this->money;
				ReefAPI::addSyogo($p ,$this->syogo);
				$p->sendMessage(ReefAPI::GOOD.'称号を購入しました');
			}else{
				$p->sendMessage(ReefAPI::BAD.'お金が足りません');
			}
		}else{
			$p->sendMessage(ReefAPI::BAD.'購入をキャンセルしました');
		}
	}
}