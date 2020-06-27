<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;
use Ree\seichi\skil\Skil;

class SkilUnlockForm implements Form
{
	/**
	 * @var array
	 */
	private $list;
	/**
	 * @var Player
	 */
	private $p;
	/**
	 * @var string
	 */
	private $string;
	public function __construct(Player $p ,string $string = "")
	{
		$this->p = $p;
		$this->string = $string;
	}

	public function jsonSerialize()
	{
		$pT = main::getpT($this->p->getName());
		foreach (Skil::SKILLIST as $skilname) {
			$skil = 'Ree\seichi\skil\\' . $skilname;
			if (!array_search($skilname, $pT->s_skil)) {
				if ($skil::getName() !== Skil::getName()) {
					$string = $skil::getName();
					$buttons[] = [
						'text' => $string
					];
					$this->list[] = $skilname;
				}
			}
		}
		$buttons[] = [
			'text' => "スキルを選択する"
		];
		$this->list[] = true;
		$buttons[] = [
			'text' => "戻る"
		];

		return [
			'type' => 'form',
			'title' => 'スキル解禁',
			'content' => $this->string."所持スキルポイント : ".$pT->s_skilpoint,
			'buttons' => $buttons,
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if (isset($this->list[$data])) {
			if ($this->list[$data] === true) {
				$p->sendForm(new SkilSelectForm($p));
				return;
			}
			$pT = main::getpT($this->p->getName());
			$skilname = $this->list[$data];
			$skil = 'Ree\seichi\skil\\' . $skilname;
			$need = $skil::getNeedSkil();
			if (array_search($need ,$this->list))
			{
				$point = main::getpT($p->getName())->s_skilpoint;
				$p->sendForm(new SkilUnlockCheckForm($skilname ,$point));
			}else{
				$p->sendForm(new SkilUnlockForm($p ,ReefAPI::BAD.'このスキルを開放するには'.$need.'を開放する必要があります'."\n"));
			}

		} else {
			$pT = main::getpT($p->getName());
			$p->sendForm(new MenuForm($pT));
		}
	}
}