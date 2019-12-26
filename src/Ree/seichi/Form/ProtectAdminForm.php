<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
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
		$x = $data["x2"] - $data["x1"];
		$z = $data["z2"] - $data["z1"];
		$count = $x * $z;
		$string = 'スタート地点 x : '.$data["x1"].'  y : '.$data["z1"]."\n".'最終地点 x : '.$data["x2"].'  z : '.$data["z2"]."\n".'土地の大きさ : '.$count."\n".'エンティティカウント : '.$data["entityCount"]."\n".'ID : '.$data["id"];
		return [
			'type' => 'form',
			'title' => '土地保護管理',
			'content' => $string,
			'buttons' => [
//				[
//					'text' => '土地にテレポートする'
//				],
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
			/*case 0:
				$p->sendMessage(ReefAPI::GOOD.'テレポートしています...');
				$level = Server::getInstance()->getLevelByName($this->data["level"]);
				var_dump(632);
				$pos = new Position($data["x1"] ,100 ,$data["z1"] ,$level);
				var_dump(1);
				$p->teleport($pos);
				break;*/

			default:
				$p->sendForm(new WorldProtectForm($p));
		}
	}
}