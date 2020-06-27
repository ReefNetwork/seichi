<?php


namespace Ree\seichi\form;


use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;
use Ree\seichi\PlayerTask;

class BuyForm implements Form
{
	/**
	 * @var Item
	 */
	private $item;
	/**
	 * @var int
	 */
	private $price;
	/**
	 * @var string
	 */
	private $name;
	public function __construct(Item $item ,int $price ,string $name = null)
	{
		$this->item = $item;
		$this->price = $price;
		if ($name)
		{
			$this->name = $name;
		}else{
			$this->name = $item->getVanillaName();
		}
	}

	public function jsonSerialize()
	{
		$string = 'アイテムの名前 : '.$this->name."\n".'1個の値段 : '.$this->price;
		return [
			'type' => 'custom_form',
			'title' => 'ショップ',
			'content' => [
				[
					"type" => "input",
					"text" => $string."\n購入する個数を入力してください",
					"placeholder" => "int",
					"default" => "",
				],
			]
		];
	}

	public function handleResponse(Player $player, $data): void
	{
		if ($data === NULL) {
			return;
		}
		if (isset ($data[0])) {
			if (!is_numeric($data[0])) {
				$player->sendMessage(ReefAPI::BAD . '数値を入力してください');
				return;
			}
			if ($data[0] <= 0)
			{
				$player->sendMessage(ReefAPI::BAD . '数値を入力してください');
				return;
			}
			$pT = main::getpT($player->getName());
			$coin = $this->price * $data[0];
			$item = $this->item;
			$item->setCount($data[0]);
			if ($this->removeCoin($pT ,$coin))
			{
				if ($player->getInventory()->canAddItem($item)) {
					$player->sendMessage(ReefAPI::GOOD.'購入しました');
					$player->getInventory()->addItem($item);
				}else{
					$player->sendMessage(ReefAPI::BAD.'インベントリがいっぱいです');
				}
			}else{
				$player->sendMessage(ReefAPI::BAD.'お金が足りません');
			}
		}
	}

	private function removeCoin(PlayerTask $pT, int $coin): bool
	{
		$pcoin = $pT->s_coin;
		if ($pcoin >= $coin) {
			$pT->s_coin = $pcoin - $coin;
			return true;
		} else {
			return false;
		}
	}
}