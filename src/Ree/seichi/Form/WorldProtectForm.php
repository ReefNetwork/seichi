<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\Player;
use Ree\reef\ReefAPI;
use Ree\seichi\main;

class WorldProtectForm implements Form
{
    public function jsonSerialize()
    {
        return [
            'type' => 'form',
            'title' => '土地保護管理',
            'content' => "",
            'buttons' => [
				[
					'text' => "土地保護をする"
				],
				[
					'text' => "整地ワールド1"
				],
				[
					'text' => "整地ワールド2"
				],
				[
					'text' => "公共施設"
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
        $server = main::getpT($p->getName());
        switch ($data) {
            case 0:
				$array[] = "クリックでメニューが開きます";
				$p->getInventory()->addItem(Item::get(Item::WOODEN_AXE)->setLore($array));
				$p->sendMessage(ReefAPI::GOOD."土地保護ようの斧を取り出しました");
                break;

			default:
				$p->sendForm(new MenuForm($server->));
        }
    }
}