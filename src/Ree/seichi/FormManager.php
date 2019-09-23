<?php

namespace Ree\seichi;

use pocketmine\math\Vector3;

use Ree\seichi\main;
use Ree\seichi\PlayerTask;

class FormManager
{
    const nulltext = "";
    const nullbutton = [];

    const error = 0;

    const main_menu = 000000;
    const world_teleport_menu = 000001;

    const skil_main_menu = 100000;

    const lobby = "lobby";
    const leveling_1 = "leveling_1";

    static public function FormReceive($pT, $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pk = $ev->getPacket();
        $data = json_decode($pk->formData);

        if ($data === null) {
            return;
        }
        switch ($pk->formId) {
            case self::main_menu:
                switch ($data) {
                    case 0:
                        $buttons[] = [
                            'text' => "back//戻る"
                        ];
                        $buttons[] = [
                            'text' => "lobby//ロビー"
                        ];
                        $buttons[] = [
                            'text' => "leveling//整地"
                        ];
                        $come = "World teleport menu//ワールドテレポートメニュー";
                        $pT->sendForm("world_teleport_menu", $come, $buttons, self::world_teleport_menu);
                        break;

                    case 1:
                        $come = "coming soon...";
                        $pT->sendForm("skil_main_menu", $come, self::nullbutton, self::skil_main_menu);
                        break;

                    default:
                        $come = "§cError";
                        $pT->sendForm("not exist", $come, self::nullbutton, self::error);
                        break;
                }
                break;

            case self::world_teleport_menu:
                switch ($data) {
                    case 0;
                        self::sendMenu($pT);
                        break;

                    case 1:
                        $level = main::getMain()->getServer()->getLevelByName(self::lobby);
                        $p->setLevel($level);
                        $p->teleport(new Vector3(0, 5, 0, $level));
                        $pT->sendLog(self::lobby."の0,100,0にテレポートしました");
                        $p->addActionBarMessage(self::lobby."にテレポートしました");
                        break;

                    case 2:
                        $level = main::getMain()->getServer()->getLevelByName(self::leveling_1);
                        $p->setLevel($level);
                        $p->teleport(new Vector3(0, 100, 0, $level));
                        $pT->sendLog(self::leveling_1."の0,100,0にテレポートしました");
                        $p->addActionBarMessage(self::leveling_1."にテレポートしました");
                        break;

                    default:
                        $come = "§cError";
                        $pT->sendForm("not exist", $come, self::nullbutton, self::error);
                        break;
                }
        }
    }

    static public function sendMenu($pT)
    {
        $buttons[] = [
            'text' => "world//ワールド"
        ];
        $buttons[] = [
            'text' => "skil//スキル"
        ];
        $buttons[] = [
            'text' => "playerdata//プレイヤーデータ"
        ];
        $buttons[] = [
            'text' => "world//ワールド"
        ];
        $pT->sendForm("main_menu", self::nulltext, $buttons, self::main_menu);
    }
}