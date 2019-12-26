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
 * Seichi MainCore
 *
 * @copyright 2019 Ree_jp
 */

namespace Ree\seichi;

use pocketmine\block\Block;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\item\Item;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use Ree\reef\ReefAPI;
use Ree\seichi\event\ChristmasGatya2019;
use Ree\seichi\form\MenuForm;
use Ree\seichi\skil\background\BreakMana;
use Ree\seichi\skil\background\Fortune;
use Ree\seichi\Task\FlyTask;
use Ree\seichi\Task\InventoryUpdateTask;
use Ree\seichi\Task\ScoreBoardTask;
use Ree\StackStrage\StackStrage_API;


class main extends PluginBase implements listener
{
	private const lobby = "lobby";
	private const form = "ModalFormResponsePacket";

	/**
	 * @var PlayerTask[]
	 */
	public $pT;

	/**
	 * @var main
	 */
	private static $main;

	/**
	 * @var Config
	 */
	private $strage;

	/**
	 * @var Config
	 */
	private $data;
	
	/**
	 * @var int[]
	 */
	private $clickTime = [];

	public function onEnable()
	{
		echo "Reef_core >> loading now...\n";
		date_default_timezone_set('Asia/Tokyo');
		self::$main = $this;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->strage = new Config($this->getDataFolder() . "user_strage.yml", Config::YAML);
		$this->data = new Config($this->getDataFolder() . "user_data.yml", Config::YAML);

//		$this->getServer()->generateLevel("lobby", time(), GeneratorManager::getGenerator("flat"));
//		$this->getServer()->generateLevel("leveling_1", time(), generatorManager::getGenerator("default"));
//		$this->getServer()->generateLevel("leveling_2", time(), generatorManager::getGenerator("default"));
//		$this->getServer()->generateLevel("public", time(), generatorManager::getGenerator("flat"));

		$this->getServer()->loadLevel("lobby");
		$this->getServer()->loadLevel("leveling_1");
		$this->getServer()->loadLevel("leveling_2");
		$this->getServer()->loadLevel("public");


		Enchantment::registerEnchantment(new Enchantment(
			Enchantment::FORTUNE,
			'fortune',
			Enchantment::RARITY_RARE,
			Enchantment::SLOT_DIG,
			Enchantment::SLOT_SHEARS,
			10
		));
		Enchantment::registerEnchantment(new Enchantment(
			Gatya::ENCHANT_ADD_MANA,
			'add_max_mana',
			Enchantment::RARITY_MYTHIC,
			Enchantment::SLOT_ALL,
			Enchantment::SLOT_SHEARS,
			9999
		));

		for ($i = 1; $i <= 9; $i++) {
			$item = Gatya::getGatya($i);
			Item::addCreativeItem($item);
		}
		$item = Gatya::getGatya(Gatya::APPLE1);
		Item::addCreativeItem($item);
		$item = Gatya::getGatya(0);
		Item::addCreativeItem($item);
		for ($i = 1; $i <= 5; $i++) {
			$item = ChristmasGatya2019::getGatya($i);
			Item::addCreativeItem($item);
		}
		$item = ChristmasGatya2019::getGatya(0);
		Item::addCreativeItem($item);

		echo "Reef_core >> Complete\n";
	}

	public function onDisable()
	{
		echo "seichi >> Saving data...\n";

		foreach (Server::getInstance()->getOnlinePlayers() as $p) {

		}

		foreach (Server::getInstance()->getOnlinePlayers() as $p) {
			$p->kick("§a✔ Saving player data and exiting server...", false);
			sleep(1);
		}
		echo "seichi >> Complete\n";
	}

	public function onJoin(PlayerJoinEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$pT = new PlayerTask($p);

		if ($this->data->exists($n)) {
			$pT->s_strage = $this->strage->get($n);
			$pT->setData($this->data->get($n));

			$pT->sendLog("plyaerデータを所得しました");
		} else {
			$p->addActionBarMessage("plyaerデータが見つからんかったためデータをデフォルトに適用します");
			$pT->sendLog("plyaerデータが見つからんかったためデータをデフォルトに適用します");
			$p->getInventory()->addItem(Item::get(Item::STICK)->setCustomName("メニュー"));
			$p->getInventory()->addItem(Item::get(Item::DIAMOND_PICKAXE));
			$p->getInventory()->addItem(Item::get(Item::DIAMOND_AXE));
			$p->getInventory()->addItem(Item::get(Item::DIAMOND_SHOVEL));
			$p->getInventory()->addItem(Item::get(Item::BAKED_POTATO, 0, 64));
			$pT->sendLog("初期装備が付与されました");
			$pT->sendLog("Setting::[HIDE]ServerLog");
			$p->sendMessage("SeverLogをhideにしました");
		}
		$this->pT[$n] = $pT;
		$pT->task["InventoryUpdateTask"] = $this->getScheduler()->scheduleRepeatingTask(new InventoryUpdateTask($pT), 50)->getTaskId();
		$pT->task["ScoreBoardTask"] = $this->getScheduler()->scheduleRepeatingTask(new ScoreBoardTask($pT), 15)->getTaskId();
		$pT->task["FlyTask"] = $this->getScheduler()->scheduleRepeatingTask(new FlyTask($pT), 1200)->getTaskId();

		$pT->updateInventory();
		$pT->createBar();
		$pT->sendScore();
		$p->addTitle("Welcom to Server", "Reef network β");
	}

	public function onQuit(PlayerQuitEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$pT = self::getpT($n);

		if (is_array($pT->task)) {
			foreach ($pT->task as $id) {
				$this->getScheduler()->cancelTask($id);
			}
		}

		$this->strage->set($n, $pT->s_strage);
		$this->data->set($n, $pT->getData());
		$this->strage->save();
		$this->data->save();
	}

	public function onEat(PlayerItemConsumeEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$pT = self::getpT($n);
		$item = $ev->getItem();

		$nbt = $item->getNamedTag();
		if ($nbt->offsetExists(Gatya::GATYA)) {
			switch ($nbt->getInt(Gatya::GATYA)) {
				case Gatya::APPLE1:
					$mana = $pT->s_mana + 300;
					if ($mana > $pT->getMaxmana()) {
						$pT->s_mana = $pT->getMaxmana();
					} else {
						$pT->s_mana = $mana;
					}
					break;

				case Gatya::APPLE2:
					$mana = $pT->s_mana + 1200;
					if ($mana > $pT->getMaxmana()) {
						$pT->s_mana = $pT->getMaxmana();
					} else {
						$pT->s_mana = $mana;
					}
					break;

				case Gatya::APPLE3:
					$mana = $pT->s_mana + 6200;
					if ($mana > $pT->getMaxmana()) {
						$pT->s_mana = $pT->getMaxmana();
					} else {
						$pT->s_mana = $mana;
					}
					break;
			}
			$pT->sendBar();
		}
	}

	/**
	 * @param BlockBreakEvent $ev
	 */
	public function onBreak(BlockBreakEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$item = $ev->getItem();
		$block = $ev->getBlock();
		$pos = $block->asPosition();
		$pT = self::getpT($n);

		if ($ev->isCancelled()) {
			return;
		}
		if (!ReefAPI::isProtect($block->asPosition() ,$p)) {
			return;
		}
		if (!$this->checkHigth($ev->getBlock()->asPosition(), $ev->getBlock()->getLevel())) {
			$p->sendTip("§c上から掘ってください");
			$ev->setCancelled();
			return;
		}
		if ($block->getId() === Block::STONE_SLAB) {
			if ($block->getFloorY() === 1) {
				$ev->setCancelled();
				$p->addActionBarMessage(ReefAPI::BAD."y座標が1ブロックのハーフブロックは破壊することが出来ません");
				return;
			}
		}
		if ($ev->getBlock()->getId() == 0) {
			$ev->setCancelled();
			return;
		}

		$pT->s_nowbreakEffect::onRun($pos);

		if (in_array($ev->getBlock()->getId(), [16, 21, 56, 129, 153])) {
			$add = Fortune::doFortune($item);
			if ($add) {
				$drop = $ev->getDrops();
				$drop[0]->setCount($add);
			}
		}
		if ($pT->s_running) {
			foreach ($ev->getDrops() as $drop) {
				StackStrage_API::add($p, $drop);
			}
			$ev->setDrops([]);
			$pT->addxp($ev->getBlock()->asVector3());
			$pT->addCoin(0.01);
			return;
		}

		BreakMana::onBreak($pT);
		$pT->sendBar();

		if ($p->isSneaking()) {
			$pT->addxp($ev->getBlock()->asVector3());
			foreach ($ev->getDrops() as $drop) {
				StackStrage_API::add($p, $drop);
			}
			$ev->setDrops([]);
			$pT->addCoin(0.1);
			return;
		}

		if ($pT->s_mana >= $pT->s_nowSkil::getMana()) {
			$pT->s_mana = $pT->s_mana - $pT->s_nowSkil::getMana();
			$pT->addCoin(0.1);
		} else {
			$p->sendTip("§cSkil発動に必要なマナが足りません");
			$pT->addxp($ev->getBlock()->asVector3());
			foreach ($ev->getDrops() as $drop) {
				StackStrage_API::add($p, $drop);
			}
			$ev->setDrops([]);
			$pT->addCoin(0.1);
			return;
		}

		if ($pT->s_coolTime)
		{
			$p->sendTip(ReefAPI::BAD.'スキルはクールタイム中です');
			$pT->addxp($ev->getBlock()->asVector3());
			foreach ($ev->getDrops() as $drop) {
				StackStrage_API::add($p, $drop);
			}
			$ev->setDrops([]);
			$pT->addCoin(0.1);
			return;
		}

		if ($pT->onbreak($ev->getBlock(), $ev->getItem())) {
			foreach ($ev->getDrops() as $drop) {
				StackStrage_API::add($p, $drop);
			}
			$pT->addxp($ev->getBlock()->asVector3());
			$ev->setDrops([]);
			return;
		}
		$pT->errer("line" . __LINE__ . " break処理に失敗しました", $this);
		$ev->setCancelled();
	}

	/**
	 * @param BlockPlaceEvent $ev
	 */
	public function onPlace(BlockPlaceEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$block = $ev->getBlock();
		$pT = self::getpT($n);

		if ($ev->getItem()->getId() == Item::MOB_HEAD) {
			$p->addActionBarMessage("§4Hey! §rSorry, but you can't place that block.");
			$ev->setCancelled();
			return;
		}
		if ($block->getId() === Block::STONE_SLAB) {
			if ($block->getFloorY() === 1) {
//				$p->getInventory()->addItem(Item::get(Item::STONE_SLAB));
//				$p->addActionBarMessage("§e+0.1coin");
//				self::getpT($n)->addCoin(0.1);
				return;
			}
		}
	}

	public function onTuch(PlayerInteractEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		$pT = self::getpT($n);
		$item = $ev->getItem();


		switch ($ev->getItem()->getId()) {
			case Item::EMERALD:
				$tag = $item->getNamedTag();
				if ($tag->offsetExists(Gatya::GATYA)) {
					if (!$p->getInventory()->canAddItem(Item::get(Item::DIAMOND_SHOVEL))) {
						$p->sendTip("§cインベントリがいっぱいです");
						return;
					}
					if ($tag->offsetExists(ChristmasGatya2019::CHRISTMAS))
					{
						$bool = ChristmasGatya2019::onGatya($p);
						if ($bool) {
							$item->setCount(1);
							$p->getInventory()->removeItem($item);
							$p->sendTip(ReefAPI::GOOD."ガチャを引きました");
						} else {
							$p->sendTip(ReefAPI::BAD."§cインベントリがいっぱいです");
						}
						return;
					}
					$bool = Gatya::onGatya($p);
					if ($bool) {
						$item->setCount(1);
						$p->getInventory()->removeItem($item);
						$p->sendTip(ReefAPI::GOOD."ガチャを引きました");
					} else {
						$p->sendTip(ReefAPI::BAD."インベントリがいっぱいです");
					}
				}
				break;

			case Item::STICK:
				if(!isset($this->clickTime[$p->getName()])) $this->clickTime[$p->getName()] = 0;
				if($this->clickTime[$p->getName()] === time()) return;
			    
				$p->sendForm(new MenuForm($pT));
				$this->clickTime[$p->getName()] = time();
				break;
		}
	}

//	public function onTransaction(InventoryTransactionEvent $ev)
//	{
//		$tr = $ev->getTransaction();
//		$inve = $tr->getInventories();
//
//
//		foreach ($inve as $inv) {
//			foreach ($tr->getActions() as $action) {
//				if ($action instanceof SlotChangeAction) {
//
//					if ($action->getInventory() instanceof PlayerInventory) {
//						$p = $ev->getTransaction()->getSource();
//						$n = $p->getName();
//						$pT = self::getpT($n);
//						$p->getInventory()->close($p);
//
//						switch ($action->getSlot()) {
//							case 19;
//								$pT->s_chestInstance = new StackStrage($pT);
//								$ev->setCancelled();
//								return;
//
//							case 20:
//								$pT->s_chestInstance = new SkilSelect($pT);
//								$ev->setCancelled();
//								return;
//
//							case 23:
//								$ev->setCancelled();
//								ChestGuiManager::CloseInventory($p, $p->x, $p->y, $p->z);
//								if (!$pT->s_fly) {
//									if ($pT->s_coin > 1) {
//										$p->sendMessage("§aフライが有効になりました");
//										$pT->s_fly = true;
//										$p->setAllowFlight(true);
//									} else {
//										$p->sendMessage("§cお金が足りません");
//									}
//								} else {
//									$p->sendMessage("§7フライを無効にしました");
//									$ev->setCancelled();
//									$pT->s_fly = false;
//									$p->setAllowFlight(false);
//									$p->setFlying(false);
//								}
//								return;
//
//							case 24:
//								$pT->s_chestInstance = new WorldSelect($pT);
//								$ev->setCancelled();
//								return;
//
//							case 25:
//								ChestGuiManager::CloseInventory($p, $p->x, $p->y, $p->z);
//								$this->getScheduler()->scheduleDelayedTask(new TeleportTask($p, $p->getLevel()->getSafeSpawn()), 10);
//								$ev->setCancelled();
//								return;
//
//							case 28:
//								$pT->s_chestInstance = new Dust($pT);
//								$ev->setCancelled();
//								return;
//
//							case 29:
//								$ev->setCancelled();
//								return;
//
//							case 30:
//								$count = 64;
//								if ($pT->s_gatya < 64) {
//									if ($pT->s_gatya <= 0) {
//										$p->sendMessage("ガチャ券がありません");
//									}
//									$count = $pT->s_gatya;
//								}
//								$item = Gatya::getGatya(0, $p);
//								$item->setCount($count);
//								if ($p->getInventory()->canAddItem($item)) {
//									$p->getInventory()->addItem($item);
//								} else {
//									$p->sendMessage("インベントリに空きがありません");
//								}
//								$pT->s_gatya -= $count;
//								$ev->setCancelled();
//								return;
//
//							case 34:
//								$ev->setCancelled();
//								ChestGuiManager::CloseInventory($p, $p->x, $p->y, $p->z);
//								$this->getScheduler()->scheduleDelayedTask(new Transfer($p), 10);
//								return;
//
//							case 35:
//								$ev->setCancelled();
//
//								$pT = $this->pT[$n];
//
//								$this->strage->set($n, $pT->s_strage);
//								$this->data->set($n, $pT->getData());
//								$this->strage->save();
//								$this->data->save();
//
//								$pT = new PlayerTask($pT->getPlayer());
//
//								$pT->s_strage = $this->strage->get($n);
//								$pT->setData($this->data->get($n));
//
//								$this->pT[$n] = $pT;
//								$pT->sendLog("plyaerデータを所得しました");
//								return;
//
//							case 0:
//							case 1:
//							case 2:
//							case 3:
//							case 4:
//							case 5:
//							case 6:
//							case 7:
//							case 8:
//								return;
//
//							default:
//								$ev->setCancelled();
//								return;
//						}
//					}
//				}
//			}
//		}
//	}

	private function checkHigth(Position $pos, Level $level, int $higut = 10)
	{
		for ($i = 0; $i <= $higut; $i++) {
			$pos = $pos->add(0, 1, 0);
			if ($level->getBlock($pos)->getId() == Item::AIR) {
				return true;
			}
		}
		return false;
	}

	public function onReceive(DataPacketReceiveEvent $ev)
	{
		$p = $ev->getPlayer();
		$n = $p->getName();
		if (isset($this->pT[$n])) {
			$pT = $this->pT[$n];

			if ($ev->getPacket()->getName() == self::form) {
				FormManager::formReceive($pT, $ev);
			}
		}
	}

	/**
	 * @param $n
	 * @return PlayerTask
	 */
	static public function getpT($n)
	{
		$main = self::$main;
		return $main->pT[$n];
	}

	/**
	 * @return main
	 */
	static public function getMain()
	{
		return self::$main;
	}

	/**
	 * @return Config
	 */
	static public function getData()
	{
		return self::getMain()->data;
	}
}
