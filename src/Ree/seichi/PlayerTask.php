<?php

namespace Ree\seichi;

use pocketmine\Player;
use pocketmine\entity\Entity;

use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;

class PlayerTask
{
    const objname = "sidebar";
    const board = "board";

    public $s_level = 0;
    public $s_coin = 0;
    public $s_mana = 100;
    public $s_coolTime = 0;
    public $s_nowSkil = null;
    public $s_skil = [];
    public $s_strage = [];
    public $s_data = [];

    public $s_open = false;
    public $s_tempopen = NULL;
    public $s_log = [
        12 => "this is server log",
        11 => "null",
        10 => "null",
        9 => "null",
        8 => "null",
        7 => "null",
        6 => "null",
        5 => "null",
        4 => "null",
        3 => "null",
        2 => "null",
        1 => "null",
        0 => "null",
    ];
    public $s_gui = null;

    public function __construct($p)
    {
        $this->p = $p;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->p;
    }

    /**
     * @param $block
     */
    public function onbreak($block)
    {
        if ($this->s_nowSkil) {

        } else {
            $this->sendLog("skilがセットされていません");
        }


    }

    /**
     * @param $title
     * @param $text
     * @param $buttons
     * @param $id
     */
    public function sendForm($title, $text, $buttons, $id)
    {
        $data = [
            'type' => 'form',
            'title' => $title,
            'content' => $text,
            'buttons' => $buttons
        ];
        $pk = new ModalFormRequestPacket();
        $pk->formId = $id;
        $pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
        $this->p->dataPacket($pk);
        $this->sendLog("ID " . $id . " のformを送信しました");
    }

    public function sendBar()
    {
        $p = $this->p;

        $pk = new BossEventPacket();
        $pk->bossEid = $p->getId();
        $pk->playerEid = $p->getId();
        $pk->eventType = BossEventPacket::TYPE_SHOW;
        $pk->healthPercent = 10;
        $pk->unknownShort = 1;
        $pk->color = 1;
        $pk->overlay = 1;
        $pk->title = "§bMana" . $this->s_mana . " \ " . ($this->s_level * 20);
        $p->dataPacket($pk);
        $this->sendLog("bossbarを送信しました");
    }

    public function sendScore()
    {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = self::objname;
        $pk->objectiveName = self::board;
        $pk->displayName = "§bYourStatus§e//§aあなたのステータス";
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $this->p->sendDataPacket($pk);

        $pk = new SetScorePacket();
        $pk->type = 0;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "                             ";
        $entry->score = 1;
        $entry->scoreboardId = 1;
        $pk->entries[1] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "  level//レベル   :   " . $this->s_level;
        $entry->score = 2;
        $entry->scoreboardId = 2;
        $pk->entries[2] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "  money//お金   :   " . $this->s_coin;
        $entry->score = 3;
        $entry->scoreboardId = 3;
        $pk->entries[3] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "  skil//スキル   :   " . $this->s_nowSkil;
        $entry->score = 4;
        $entry->scoreboardId = 4;
        $pk->entries[4] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "     ";
        $entry->score = 5;
        $entry->scoreboardId = 5;
        $pk->entries[5] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "      ";
        $entry->score = 6;
        $entry->scoreboardId = 6;
        $pk->entries[6] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "       ";
        $entry->score = 7;
        $entry->scoreboardId = 7;
        $pk->entries[7] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "        ";
        $entry->score = 8;
        $entry->scoreboardId = 8;
        $pk->entries[8] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "         ";
        $entry->score = 9;
        $entry->scoreboardId = 9;
        $pk->entries[9] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "          ";
        $entry->score = 10;
        $entry->scoreboardId = 10;
        $pk->entries[10] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "           ";
        $entry->score = 11;
        $entry->scoreboardId = 11;
        $pk->entries[11] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "  news//ニュース";
        $entry->score = 12;
        $entry->scoreboardId = 12;
        $pk->entries[12] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "             ";
        $entry->score = 13;
        $entry->scoreboardId = 13;
        $pk->entries[13] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "   開発中";
        $entry->score = 14;
        $entry->scoreboardId = 14;
        $pk->entries[14] = $entry;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = self::board;
        $entry->type = 3;
        $entry->customName = "               ";
        $entry->score = 15;
        $entry->scoreboardId = 15;
        $pk->entries[15] = $entry;

        $this->p->sendDataPacket($pk);

        $this->sendLog("scorebordを送信しました");
    }

    /**
     * @param $log
     */
    public function sendLog($log)
    {
        $logcount = 12;
        foreach ($this->s_log as $addlog) {
            $logcount--;
            $this->s_log[$logcount] = $addlog;
        }
        $this->s_log[12] = $log;
        $this->p->sendPopup("ServerLog\n-----------------\n1>>" . $this->s_log[12] . "\n2>>" . $this->s_log[11] . "\n3>>" . $this->s_log[10] . "\n4>>" . $this->s_log[9] . "\n5>>" . $this->s_log[8] . "\n6>>" . $this->s_log[7] . "\n7>>" . $this->s_log[6] . "\n8>>" . $this->s_log[5] . "\n9>>" . $this->s_log[4] . "\n10>>" . $this->s_log[3] . "\n11>>" . $this->s_log[2] . "\n12>>" . $this->s_log[1] . "\n13>>" . $this->s_log[0]);
    }


}
