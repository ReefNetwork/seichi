<?php

namespace Ree\seichi\form;

use pocketmine\Player;
use Ree\seichi\main;

class SkilCheckForm implements \pocketmine\form\Form
{
	/**
	 * @var string
	 */
	private $skil;

	public function __construct(string $skil)
	{
		$this->skil = $skil;
	}

	public function jsonSerialize()
	{
		$skil = 'Ree\seichi\skil\\' . $this->skil;
		$time = $skil::getCoolTime() / 20 .'秒';
		$string = 'スキル: '.$skil::getName()."\n".'§9マナ消費量 : '.$skil::getMana()."\n".'§eクールタイム : '.$time;
		return [
			'type' => 'modal',
			'title' => 'スキル選択',
			'content' => "$string",
			"button1" => "スキルをセットする",
			"button2" => "戻る",
		];
	}

	public function handleResponse(\pocketmine\Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		$server = main::getMain();
		if ($data)
		{
			$skil = 'Ree\seichi\skil\\' . $this->skil;
			$pT = main::getpT($p->getName());
			$pT->s_nowSkil = $skil;
			$p->sendForm(new MenuForm($pT ,"§a>> §rスキルを変更しました"));
		}else{
			$p->sendForm(new SkilSelectForm($p));
		}
	}
}