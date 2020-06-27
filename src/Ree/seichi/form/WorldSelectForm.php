<?php

namespace Ree\seichi\form;

use pocketmine\Player;
use Ree\seichi\main;

class WorldSelectForm implements \pocketmine\form\Form
{
    public function jsonSerialize()
    {
        return [
            'type' => 'form',
            'title' => 'ワールド移動',
            'content' => "",
            'buttons' => [
				[
					'text' => "ロビー"
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
        $server = main::getMain();
        switch ($data) {
            case 0:
				$p->sendMessage("§a>> §rロビーにテレポートしています...");
            	$level = $server->getServer()->getLevelByName("lobby");
                $p->teleport($level->getSafeSpawn());
                break;

			case 1:
				$p->sendMessage("§a>> §r整地ワールド1にテレポートしています...");
				$level = $server->getServer()->getLevelByName("leveling_1");
				$p->teleport($level->getSafeSpawn());
				break;

			case 2:
				$p->sendMessage("§a>> §r整地ワールド2にテレポートしています...");
				$level = $server->getServer()->getLevelByName("leveling_2");
				$p->teleport($level->getSafeSpawn());
				break;

			case 3:
				$p->sendMessage("§a>> §r公共施設にテレポートしています...");
				$level = $server->getServer()->getLevelByName("public");
				$p->teleport($level->getSafeSpawn());
				break;

			case 4:
				$pT = main::getpT($p->getName());
				$p->sendForm(new MenuForm($pT));
        }
    }
}