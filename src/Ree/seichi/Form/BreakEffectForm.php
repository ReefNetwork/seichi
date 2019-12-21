<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class BreakEffectForm implements Form
{
	/**
	 * @var Player
	 */
	private $p;
	/**
	 * @var array
	 */
	private $list;
	public function __construct(Player $p)
	{
		$this->p = $p;
	}

	public function jsonSerialize()
	{
		$pT = main::getpT($this->p->getName());
		$buttons = [];
		foreach ($pT->s_breakEffect as $effect)
		{
			$effect = 'Ree\seichi\skil\background\\' . $effect;
			$buttons[] = [
				'text' => $effect::getName()
			];
			$this->list[] = $effect;
		}
		$buttons[] = [
			'text' => '戻る'
		];
		$string = $pT->s_nowbreakEffect::getName();
		return [
			'type' => 'form',
			'title' => 'エフェクト変更',
			'content' => '現在のエフェクト : '.$string,
			'buttons' =>$buttons,
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if (isset($this->list[$data]))
		{
			$pT = main::getpT($this->p->getName());
			$pT->s_nowbreakEffect = $this->list[$data];
			$p->sendForm(new MenuForm($pT ,ReefAPI::GOOD."エフェクトを変更しました"));
		}else{
			$p->sendForm(new SkilSelectForm($p));
		}
	}
}