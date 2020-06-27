<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\Player;
use Ree\reef\ReefAPI;

class ProtectShareForm implements Form
{
	/**
	 * @var int
	 */
	private $id;

	public function __construct(int $id)
	{
		$this->id = $id;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if (isset($data[0]))
		{
			$bool = ReefAPI::addProtectShare($data[0] ,$this->id);
			if ($bool)
			{
				$player->sendMessage(ReefAPI::GOOD.'成功しました');
			}else{
				$player->sendMessage(ReefAPI::ERROR.'失敗しました');
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'type' => 'custom_form',
			'title' => '土地保護管理',
			'content' => [
				[
					"type" => "input",
					"text" => "共有するプレイヤーの名前を入力してください",
					"placeholder" => "string",
					"default" => "",
				],
			]
		];
	}
}