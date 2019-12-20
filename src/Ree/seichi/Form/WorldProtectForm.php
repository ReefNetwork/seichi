<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\form\ProtectForm;
use Ree\reef\main;
use Ree\reef\ReefAPI;

class WorldProtectForm implements Form
{
	/**
	 * @var Player
	 */
	private $p;
	/**
	 * @var array[]
	 */
	private $list;
	public function __construct(Player $p)
	{
		$this->p = $p;
	}

	public function jsonSerialize()
	{
		$buttons[] = [
			'text' => '土地保護をする'
		];
		$i = 1;
		if (main::getMain()->getProtect()->exists($this->p->getName()))
		{
			foreach (main::getMain()->getProtect()->get($this->p->getName()) as $world)
			{
				foreach ($world as $data)
				{
					$buttons[] = [
						'text' => "ID : ".$data["id"]."\n".'の土地を管理する'
					];
					$this->list[$i] = $data;
					$i++;
				}
			}
		}

		$buttons[] = [
			'text' => '戻る'
		];

		return [
			'type' => 'form',
			'title' => '土地保護管理',
			'content' => '',
			'buttons' => $buttons,
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		switch ($data) {
			case 0:
				$array[] = "クリックでメニューが開きます";
				$p->getInventory()->addItem(Item::get(Item::WOODEN_AXE)->setLore($array));
				$p->sendMessage(ReefAPI::GOOD . "土地保護用の斧を取り出しました");
				break;

			default:
				if (isset($this->list[$data]))
				{
					$p->sendForm(new ProtectAdminForm($p));
				}else{
					$pT = \Ree\seichi\main::getpT($p->getName());
					$p->sendForm(new MenuForm($pT));
				}
		}
	}
}