<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;

class ProtectAdminForm implements Form
{
	/**
	 * @var Player
	 */
	private $p;
	/**
	 * @var array
	 */
	private $data;
	public function __construct(Player $p ,array $data)
	{
		$this->p = $p;
		$this->data = $data;
	}

	public function jsonSerialize()
	{
		$data = $this->data;
		$string = 'スタート地点 x : '.$data["x1"].'  y : '.$data["z1"]."\n".'最終地点 x : '.$data["x2"].'  z : '.$data["z2"]."\n".'エンティティカウント : '.$data["entityCount"]."\n".'ID : '.$data["id"];
		return [
			'type' => 'form',
			'title' => '土地保護管理',
			'content' => $string,
			'buttons' => [
				[
					'text' => 'Coming Soon'
				],
				[
					'text' => "戻る"
				],
			]
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}
		switch ($data) {
			case 0:
				break;

			default:
				$p->sendForm(new WorldProtectForm($p));
		}
	}
}