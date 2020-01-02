<?php

/*
 * _____                  _
 *|  __ \                (_)
 *| |__) |___  ___ ______ _ _ __
 *|  _  // _ \/ _ \______| | '_ \
 *| | \ \  __/  __/      | | |_) |
 *|_|  \_\___|\___|      | | .__/
 *                      _/ | |
 *                     |__/|_|
 *
 * Seichi PlayerCore
 *
 * @copyright 2019 Ree_jp
 */

namespace Ree\seichi;

use mysql_xdevapi\Exception;
use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;

use pocketmine\Server;
use Ree\reef\ReefAPI;
use Ree\seichi\form\SkilUnlockForm;
use Ree\seichi\skil\background\BreakEffect;
use Ree\seichi\skil\Skil;
use Ree\seichi\Task\CoolTimeTask;
use Ree\seichi\Task\ImmobileTask;
use Ree\seichi\Task\TeleportTask;
use Ree\StackStrage\ChestGuiManager;
use Ree\StackStrage\StackStrage_API;
use Ree\StackStrage\Virchal\StackStrage;
use xenialdan\apibossbar\BossBar;

class PlayerTask
{
	const objname = "sidebar";
	const board = "board";

	/**
	 * @var Player
	 */
	public $p = NULL;

	/**
	 * @var Skil
	 *
	 * PlayerNowSkil
	 */
	public $s_nowSkil = NULL;

	/**
	 * @var BreakEffect
	 */
	public $s_nowbreakEffect;

	/*
	 * @var array
	 *
	 * StackStrageData
	 */
	public $s_strage = [];

	/**
	 * @var int
	 *
	 * PlayerSeichiLevel
	 */
	public $s_level = 0;

	/**
	 * @var float
	 *
	 * PlayerMoney
	 */
	public $s_coin = 0;

	/**
	 * @var int
	 *
	 * SeichiExperience
	 */
	public $s_experience = 0;

	/**
	 * @var int
	 *
	 * PlayerMana
	 */
	public $s_mana = 100;

	/**
	 * @var array
	 *
	 * SkilUnlockList
	 */
	public $s_skil = ["Skil"];

	/**
	 * @var array
	 */
	public $s_breakEffect = ["BreakEffect"];

	/**
	 * @var int
	 *
	 * SkilUnlockPoint
	 */
	public $s_skilpoint = 0;

	/**
	 * @var int
	 *
	 * SeichiGatya
	 */
	public $s_gatya = 10;

	/**
	 * @var int
	 *
	 * SkilCoolTime
	 */
	public $s_coolTime = 0;

	/**
	 * @var StackStrage
	 */
	public $s_chestInstance = NULL;

	/**
	 * @var string
	 *
	 * ChestGuiType
	 */
	public $s_open = false;

	/**
	 * @var int
	 *
	 * LevelupNeedXp
	 */
	public $s_needxp = 1;

	public $s_tempdata = NULL;
	public $s_fly = false;
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
	public $s_running = false;

	/**
	 * @var BossBar
	 */
	private $bar;

	/**
	 * @var int[]
	 */
	public $task = [];

	/**
	 * @var bool
	 *
	 * isPlayerDataSave
	 */
	private $save = true;

	/**
	 * PlayerTask constructor.
	 * @param Player $p
	 */
	public function __construct($p)
	{
		$this->p = $p;
		$this->s_nowSkil = 'Ree\seichi\skil\\' . "Skil";
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player
	{
		return $this->p;
	}

	/**
	 * @param array $data
	 */
	public function setData($data): void
	{
		$this->s_level = $data["level"];
		$this->s_skil = $data["skil"];
		$this->s_skilpoint = $data["skilpoint"];
		$this->s_mana = $data["mana"];
		$this->s_coin = $data["coin"];
		$this->s_experience = $data["experience"];
		$this->s_gatya = $data["gatya"];

		if (isset ($data["breakEffect"])) {
			$this->s_breakEffect = $data["breakEffect"];
		}
		if (isset ($data["now_breakEffect"])) {
			$class = $data["now_breakEffect"];
		} else {
			$class = BreakEffect::getClassName();
		}
		$effect = 'Ree\seichi\skil\background\\' . $class;
		if (!class_exists($effect)) {
			$this->getPlayer()->sendMessage(ReefAPI::ERROR."1部セーブデータが破損しています");
			$class = BreakEffect::getClassName();
			$effect = 'Ree\seichi\skil\background\\' . $class;
			$this->getPlayer()->sendMessage(ReefAPI::ERROR."セーブデータを修復しました1部設定がリセットされてる可能性があります");
			if (!class_exists($effect))
			{
				$this->save = false;
				$this->getPlayer()->kick(ReefAPI::ERROR."エラーが発生しました");
			}
		}
		$this->s_nowbreakEffect = $effect;

		$skil = $data["nowskil"];
		$skil = 'Ree\seichi\skil\\' . $skil;
		$this->s_nowSkil = $skil;

		$this->s_needxp = $this->getNeedxp();
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		if ($this->save)
		{
			$data["level"] = $this->s_level;
			$data["skil"] = $this->s_skil;
			$data["breakEffect"] = $this->s_breakEffect;
			$data["skilpoint"] = $this->s_skilpoint;
			$data["mana"] = $this->s_mana;
			$data["coin"] = $this->s_coin;
			$data["experience"] = $this->s_experience;
			$data["gatya"] = $this->s_gatya;

			try {
				$data["nowskil"] = $this->s_nowSkil::getClassName();
//				$data["now_breakEffect"] = $this->s_nowbreakEffect::getClassName();
			} catch (\Exception $ex) {
				$data["nowskil"] = Skil::getClassName();
				$data["now_breakEffect"] = BreakEffect::getClassName();
				Server::getInstance()->broadcastMessage(ReefAPI::ERROR . "データ保存でエラーが発生しました");
			}
		}



		return $data;
	}

	/**
	 * @return int
	 *
	 * MaxMana
	 */
	public function getMaxmana(): int
	{
		switch ($this->s_level) {
			case 0:
				return 1;

			case 1:
				return 30;

			case 2:
				return 60;

			case 3:
				return 80;

			case 4:
				return 100;
		}
		$level = $this->s_level;

		$level = $level - 4;
		$mana = $level * 13;
		$mana = $mana + 100;

		return $mana;
	}

	/**
	 * @return int
	 *
	 * LevelupNeedXp
	 */
	private function getNeedxp(): int
	{
		switch ($this->s_level) {
			case 0:
				return 1;

			case 1:
				return 100;

			case 2:
				return 300;

			case 3:
				return 600;

			case 4:
				return 900;

			case 5:
				return 1500;

			case 6:
				return 2100;

			case 7:
				return 3000;

			case 8:
				return 4000;

			case 9:
				return 5200;

			case 10:
				return 6500;
		}
		$level = $this->s_level;
		$old = 6500;


		for ($i = 11; $i <= $level; $i++) {
			$oldlevel = $level - $i;
			$oldxp = $oldlevel * 1500;
			$old = $old + $oldxp;
		}
		$new = $level - 10;
		$new = $new * 1500;

		return $old + $new;
	}

	/**
	 * @param Vector3 $vec3
	 *
	 * player addxp action
	 */
	public function addxp(Vector3 $vec3 = NULL): void
	{
		$this->s_experience++;
		if ($this->s_experience % 1000 == 0) {
			$this->s_gatya++;
			$this->getPlayer()->sendTip(ReefAPI::GOOD."1000ブロック採掘したためガチャ券をゲットしました");
		}

		$need = $this->getNeedxp();
		if (!$this->s_level) {
			$this->levelup();
			$this->s_needxp = $this->getNeedxp();
			$this->getPlayer()->sendMessage("levelupしてskilpointを手に入れました\nこれで最初のスキルをアンロックしてみましょう");
			$this->getPlayer()->sendForm(new SkilUnlockForm($this->getPlayer() ,'levelupしてskilpointを手に入れました'."\n".'これで最初のスキルをアンロックしてみましょう'));
		}
		if ($need <= $this->s_experience) {
			if ($this->s_level >= 200) {
				return;
		}
			$this->levelup();
			$this->s_needxp = $this->getNeedxp();
		}
	}

	/**
	 * player levelup action
	 */
	private function levelup(): void
	{
		$old = $this->s_level;
		$this->s_level++;
		$this->getPlayer()->addTitle("§eLevelUP §d" . $old . " -> §c" . $this->s_level, "§3Skilpoint§2を5手に入れました");
		$this->s_skilpoint = $this->s_skilpoint + 5;
		$this->getPlayer()->setHealth($this->getPlayer()->getMaxHealth());
		$this->getPlayer()->setFood($this->getPlayer()->getMaxFood());
	}

	/**
	 * @param Block $block
	 * @param Item $item
	 * @return bool
	 */
	public function onbreak(Block $block, Item $item): bool
	{
		if ($this->s_nowSkil) {
			$skil = $this->s_nowSkil;
			if ($this->s_nowSkil::getCoolTime())
			{
				$this->s_coolTime = $this->s_nowSkil::getCoolTime();
				main::getMain()->getScheduler()->scheduleDelayedTask(new CoolTimeTask($this) ,$this->s_nowSkil::getCoolTime());
			}
			$space = $this->s_nowSkil::getSpace($block, $block->getFloorX(), $block->getFloorY(), $block->getFloorZ(), $this->getPlayer());
			$this->s_running = true;
			foreach ($space as $vec3) {
				$bl = $this->getPlayer()->getLevel()->getBlock($vec3);
				if ($vec3 == $block->asVector3()) {
				} elseif ($bl->getId() !== 0) {
					$bl->level->useBreakOn($vec3, $item, $this->getPlayer());
				}
			}
			$this->s_running = false;
			return true;
		} else {
			$this->errer("line" . __LINE__ . " スキルが認識されませんでした", $this);
			return false;
		}
	}

	/**
	 * @param float $money
	 * @return bool
	 */
	public function addCoin(float $money)
	{
		$this->s_coin = $money + $this->s_coin;
		return true;
	}

	/**
	 * @param float $money
	 * @return bool
	 */
	public function setCoin(float $money)
	{
		$this->s_coin = $money;
		return true;
	}

	/**
	 * @param float $money
	 * @return bool
	 */
	public function removeCoin(float $money)
	{
		$this->s_coin = $this->s_coin - $money;
		return true;
	}

	/**
	 * @param $title
	 * @param $text
	 * @param $buttons
	 * @param $id
	 * @deprecated
	 *
	 */
	public function sendForm($title, $text, $buttons, $id): void
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

	public function updateInventory(): void
	{
		$p = $this->getPlayer();
		if (!$p instanceof Player) {
			return;
		}
		if (!$p->isOnline()) {
			return;
		}

//        for ($i = 9; $i <= 35; $i++) {
//            $item = Item::get(106, 0, 1);
//            $item->setCustomName("§0");
//            $p->getInventory()->setItem($i, $item);
//        }
//        $item = Item::get(54, 0, 1);
//        $item->setCustomName("StackStrage_β");
//        $p->getInventory()->setItem(19, $item);
//
//        $item = Item::get(340, 0, 1);
//        $item->setCustomName("スキル設定");
//        $p->getInventory()->setItem(20, $item);
//
//        $item = Item::get(Item::FEATHER, 0, 1);
//        if ($this->s_fly)
//        {
//            $bool = "§7無効";
//        }else{
//            $bool = "§a有効";
//        }
//        $item->setCustomName("フライを".$bool."§rにする");
//        $p->getInventory()->setItem(23, $item);
//
//        $item = Item::get(345, 0, 1);
//        $item->setCustomName("ワールド移動");
//        $p->getInventory()->setItem(24, $item);
//
//        $item = Item::get(138, 0, 1);
//        $item->setCustomName("スポーン地点に戻る");
//        $p->getInventory()->setItem(25, $item);
//
//        $item = Item::get(325, 10, 1);
//        $item->setCustomName("ゴミ箱");
//        $p->getInventory()->setItem(28, $item);
//
//        $item = Item::get(387, 0, 1);
//        $item->setCustomName("チャットを送信する(未実装)");
//        $p->getInventory()->setItem(29, $item);
//
//        $item = Item::get(386, 0, 1);
//        $item->setCustomName("所持ガチャ券 : ".$this->s_gatya);
//        $p->getInventory()->setItem(30, $item);
//
//        $item = Item::get(Item::DRAGON_EGG, 0, 1);
//        $item->setCustomName("同盟鯖");
//        $p->getInventory()->setItem(34, $item);

		for ($i = 0; $i <= 35; $i++) {
			$item = $p->getInventory()->getItem($i);
			$nbt = $item->getNamedTag();
			if ($nbt->offsetExists(StackStrage_API::STRAGE)) {
				if ($nbt->offsetExists(Gatya::GATYA)) {
					$count = $item->getCount();
					$data = $nbt->getInt(Gatya::GATYA);
					$new = Gatya::getGatya($data, $p);
					$new->setCount($count);
					$p->getInventory()->setItem($i, $new);
				} else {
					$new = Item::get($item->getId(), $item->getDamage(), $item->getCount());
					if ($item->hasEnchantments()) {
						foreach ($item->getEnchantments() as $ench) {
							$new->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($ench->getId()), $ench->getLevel()));
						}
					}
					$p->getInventory()->setItem($i, $new);
				}
			}
		}
	}

	public function getLevel()
	{
		return $this->s_level;
	}

	public function sendBar(): void
	{
		$bar = $this->bar;
		$string = "§bMana" . $this->s_mana . "/" . $this->getMaxmana();
		if (!$this->getPlayer()->isOnline()) {
			return;
		}
		if (!is_string($string)) {
			self::errer("line" . __LINE__ . " bossbarを正常に送れませんでした", $this);
		}
		$bar->setTitle($string);
		$par = $this->s_mana / $this->getMaxmana();
		$bar->setPercentage($par);

		$this->sendLog("bossbarを更新しました");
	}

	public function createBar(): void
	{
		$bar = new BossBar();
		$bar->setTitle("§bMana" . $this->s_mana . "/" . $this->getMaxmana());
		$par = $this->s_mana / $this->getMaxmana();
		$bar->setPercentage($par);
		$bar->addPlayer($this->getPlayer());
		$this->bar = $bar;

		$this->sendLog("bossbarを作成し、送信しました");
	}

	public function sendScore(): void
	{
		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = self::board;
		$this->getPlayer()->sendDataPacket($pk);
		$data = date("Y/m/d");
		$color = mt_rand(1, 14);
		switch ($color) {
			case 10:
				$color = "a";
				break;
			case 11:
				$color = "b";
				break;
			case 12:
				$color = "c";
				break;
			case 13:
				$color = "d";
				break;
			case 14:
				$color = "e";
				break;
		}

		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = self::objname;
		$pk->objectiveName = self::board;
		$pk->displayName = "§aReef§eNetwork §" . $color . "Beta";
		$pk->criteriaName = "dummy";
		$pk->sortOrder = 0;
		$this->p->sendDataPacket($pk);

		$pk = new SetScorePacket();
		$pk->type = 0;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "§8" . $data;
		$entry->score = 1;
		$entry->scoreboardId = 1;
		$pk->entries[1] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  レベル   :   " . $this->s_level;
		$entry->score = 2;
		$entry->scoreboardId = 2;
		$pk->entries[2] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "§m  お金   :   " . $this->s_coin;
		$entry->score = 3;
		$entry->scoreboardId = 3;
		$pk->entries[3] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  スキル   :   " . $this->s_nowSkil::getName();
		$entry->score = 4;
		$entry->scoreboardId = 4;
		$pk->entries[4] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  経験値   :   " . $this->s_experience . "//" . $this->s_needxp;
		$entry->score = 5;
		$entry->scoreboardId = 5;
		$pk->entries[5] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  マナ   :   " . $this->s_mana . "//" . $this->getMaxmana();
		$entry->score = 6;
		$entry->scoreboardId = 6;
		$pk->entries[6] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  x座標   :   " . $this->getPlayer()->getFloorX();
		$entry->score = 7;
		$entry->scoreboardId = 7;
		$pk->entries[7] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  y座標   :   " . $this->getPlayer()->getFloorY();
		$entry->score = 8;
		$entry->scoreboardId = 8;
		$pk->entries[8] = $entry;

		$entry = new ScorePacketEntry();
		$entry->objectiveName = self::board;
		$entry->type = 3;
		$entry->customName = "  z座標   :   " . $this->getPlayer()->getFloorZ();
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
		$entry->customName = "  ニュース";
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
		$entry->customName = "   " . ReefAPI::$news;
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

		$this->sendLog("scoreboardを送信しました");
	}

	/**
	 * @param string $log
	 */
	public function sendLog(string $log): void
	{
		return;

		$logcount = 12;
		foreach ($this->s_log as $addlog) {
			$logcount--;
			$this->s_log[$logcount] = $addlog;
		}
		$this->s_log[12] = $log;
		$this->p->sendPopup("ServerLog\n-----------------\n1>>" . $this->s_log[12] . "\n2>>" . $this->s_log[11] . "\n3>>" . $this->s_log[10] . "\n4>>" . $this->s_log[9] . "\n5>>" . $this->s_log[8] . "\n6>>" . $this->s_log[7] . "\n7>>" . $this->s_log[6] . "\n8>>" . $this->s_log[5] . "\n9>>" . $this->s_log[4] . "\n10>>" . $this->s_log[3] . "\n11>>" . $this->s_log[2] . "\n12>>" . $this->s_log[1] . "\n13>>" . $this->s_log[0]);
	}

	/**
	 * @param string $reason
	 * @param null $class
	 */
	public function errer(string $reason, $class = NULL): void
	{
		$this->getPlayer()->setImmobile(true);
		main::getpT($this->p->getName())->sendLog("PlayerTask>>Immobileを設定しました");
		if ($this->getPlayer()->getLevel()->getName() == "lobby") {
			main::getMain()->getScheduler()->scheduleDelayedTask(new TeleportTask($this->getPlayer(), main::getMain()->getServer()->getLevelByName("public")->getSafeSpawn()), 20);
			$this->getPlayer()->addTitle("§cERRER", "§cPleas wait 5 second", 0, 75, 0);
			$this->getPlayer()->sendMessage("§cerrer description : " . get_class($class) . " での処理中にerrorが発生しました");
			$this->getPlayer()->sendMessage("§cerrer reason : " . $reason);
		} else {
			main::getMain()->getScheduler()->scheduleDelayedTask(new TeleportTask($this->getPlayer(), main::getMain()->getServer()->getLevelByName("lobby")->getSafeSpawn()), 20);
			$this->getPlayer()->addTitle("§cERRER", "§cPleas wait 5 second", 0, 75, 0);
			$this->getPlayer()->sendMessage("§cerrer description : " . get_class($class) . " での処理中にerrorが発生しました");
			$this->getPlayer()->sendMessage("§cerrer reason : " . $reason);
		}
		ChestGuiManager::CloseInventory($this->getPlayer(), $this->getPlayer()->x, $this->getPlayer()->y, $this->getPlayer()->z);
		main::getMain()->getScheduler()->scheduleDelayedTask(new ImmobileTask($this->getPlayer()), 100);
	}

}
