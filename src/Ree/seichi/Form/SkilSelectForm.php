<?php

namespace Ree\seichi\form;

use pocketmine\entity\helper\EntitySlimeMoveHelper;
use pocketmine\Player;
use Ree\seichi\main;

class SkilSelectForm implements \pocketmine\form\Form
{
	/**
	 * @var array
	 */
	private $list;
	/**
	 * @var Player
	 */
	private $p;
	public function __construct(Player $p)
	{
		$this->p = $p;
	}

	public function jsonSerialize()
    {
		foreach (main::getpT($this->p->getName())->s_skil as $skilname) {
			$skil = 'Ree\seichi\skil\\' . $skilname;
			$string = $skil::getName();
			$buttons[] = [
				'text' => $string
			];
			$this->list[] = $skilname;
		}
		$buttons[] = [
			'text' => "スキルをアンロックする"
		];
		$this->list[] = true;
		$buttons[] = [
			'text' => "戻る"
		];

        return [
            'type' => 'form',
            'title' => 'スキル選択',
            'content' => "",
            'buttons' => $buttons,
        ];
    }

    public function handleResponse(\pocketmine\Player $p, $data): void
    {
        if ($data === NULL) {
            return;
        }
        if (isset($this->list[$data]))
		{
			if ($this->list[$data] === true)
			{
				$p->sendForm(new SkilUnlockForm($p));
				return;
			}
			$p->sendForm(new SkilCheckForm($this->list[$data]));
		}else{
			$pT = main::getpT($p->getName());
			$p->sendForm(new MenuForm($pT));
		}
    }
}