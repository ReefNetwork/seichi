<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\Player;
use Ree\seichi\main;

class SkilUnlockCheckForm implements Form
{
	/**
	 * @var string
	 */
	private $skil;
	/**
	 * @var int
	 */
	private $point;
	public function __construct(string $skil ,int $point)
	{
		$this->skil = $skil;
		$this->point = $point;
	}

	public function jsonSerialize()
	{
		$skil = 'Ree\seichi\skil\\' . $this->skil;
		$time = $skil::getCoolTime() / 20 .'秒';
		$string = 'スキル: '.$skil::getName()."\n".'所持スキルポイント : '.$this->point."\n".'§1アンロックに必要なスキルポイント : '.$skil::getSkilPoint()."\n".'§9マナ消費量 : '.$skil::getMana()."\n".'§eクールタイム : '.$time;
		return [
			'type' => 'modal',
			'title' => 'スキル解禁',
			'content' => "$string",
			"button1" => "スキルをアンロックする",
			"button2" => "戻る",
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		$server = main::getMain();
		if ($data)
		{
			$pT = main::getpT($p->getName());
			$skil = 'Ree\seichi\skil\\' . $this->skil;
			if ($pT->s_skilpoint >= $skil::getSkilpoint()) {
				$pT->s_skilpoint = $pT->s_skilpoint - $skil::getSkilpoint();
				$pT->s_skil[] = $skil::getClassName();
				$p->sendForm(new MenuForm($pT ,"§a>> §rスキルを解禁しました"));
			}else{
				$p->sendForm(new MenuForm($pT ,"§6>> §rスキルポイントが足りません"));
			}

		}else{
			$p->sendForm(new SkilUnlockForm($p));
		}
	}
}