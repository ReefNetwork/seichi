<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use Ree\reef\ReefAPI;

class ProtectRemoveCheckForm implements Form
{
	/**
	 * @var array
	 */
	private $data;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public function jsonSerialize()
	{
		return [
			'type' => 'modal',
			'title' => '土地保護管理',
			'content' => '土地を本当に削除しますか?'."\n".'§c取り消し出来ません',
			'button1' => '削除する',
			'button2' => '戻る',
		];
	}

	public function handleResponse(Player $p, $data): void
	{
		if ($data === NULL) {
			return;
		}

		if ($data)
		{
			$bool = ReefAPI::removeProtect($this->data['id']);
			if ($bool)
			{
				$p->sendMessage(ReefAPI::GOOD.'土地を削除しました');
			}else{
				$p->sendMessage(ReefAPI::ERROR.'不明なエラーが発生しました');
			}

		}else{
			$p->sendForm(new ProtectAdminForm($p ,$this->data));
		}
	}
}