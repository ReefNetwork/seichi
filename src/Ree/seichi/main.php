<?php

namespace Ree\seichi;


use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\DoubleChestInventory;

use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\server\DataPacketReceiveEvent;



class main extends PluginBase implements listener
{
    private const lobby = "lobby";
    private const form = "ModalFormResponsePacket";

    private static $main;

    public function onEnable()
    {
        echo "Seichi_core>>loading now...\n";
        date_default_timezone_set('Asia/Tokyo');
        self::$main = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->level = new Config($this->getDataFolder() . "user_level.yml", Config::YAML);
        $this->skil = new Config($this->getDataFolder() . "user_skil.yml", Config::YAML);
        $this->mana = new config($this->getDataFolder() . "user_mana.yml", Config::YAML);
        $this->coin = new Config($this->getDataFolder() . "user_coin.yml", Config::YAML);
        $this->strage = new Config($this->getDataFolder() . "user_strage.yml", Config::YAML);
        $this->data = new Config($this->getDataFolder() . "user_data.yml", Config::YAML);
        $this->getServer()->loadLevel("lobby");
        $this->getServer()->loadLevel("leveling_1");
        sleep(1);
        echo "Seichi_core>>complete\n";
    }

    public function onDisable()
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $p)
        {

        }

        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            $p->kick("§a✔ Saving player data and exiting server...", false);
            sleep(1);
        }

        echo "Seichi>>disable...\n";
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pT = new PlayerTask($p);

        if ($this->level->exists($n)) {
            $pT->s_level = $this->level->get($n);
            $pT->s_skil = $this->skil->get($n);
            $pT->s_mana = $this->mana->get($n);
            $pT->s_coin = $this->coin->get($n);
            $pT->s_strage = $this->strage->get($n);
            $pT->s_data = $this->data->get($n);
            $ev->setJoinMessage("§ajoin>>" . $n);
            $pT->sendLog("plyaerデータを所得しました");
        } else {
            $ev->setJoinMessage("§bnewjoin>>" . $n);
            $p->addActionBarMessage("plyaerデータが見つからんかったためデータをデフォルトに適用します");
            $pT->sendLog("plyaerデータが見つからんかったためデータをデフォルトに適用します");
        }

        $this->pT[$n] = $pT;

        for ($i = 9; $i <= 35; $i++) {
            $item = Item::get(106, 0, 1);
            $item->setCustomName("§0");
            $p->getInventory()->setItem($i, $item);
        }
        $item = Item::get(54, 0, 1);
        $item->setCustomName("StackStrage_β");
        $p->getInventory()->setItem(19, $item);

        $item = Item::get(340, 0, 1);
        $item->setCustomName("スキル設定");
        $p->getInventory()->setItem(20, $item);

        $item = Item::get(345, 0, 1);
        $item->setCustomName("ワールド移動");
        $p->getInventory()->setItem(24, $item);

        $item = Item::get(138, 0, 1);
        $item->setCustomName("スポーン地点に戻る");
        $p->getInventory()->setItem(25, $item);

        $item = Item::get(325, 10, 1);
        $item->setCustomName("ゴミ箱");
        $p->getInventory()->setItem(28, $item);

        $item = Item::get(387, 0, 1);
        $item->setCustomName("チャットを送信する(未実装)");
        $p->getInventory()->setItem(29, $item);

        $item = Item::get(106, 0, 1);
        $item->setCustomName($n);
        $p->getInventory()->setItem(35, $item);

        $pT->sendBar();
        $pT->sendScore();
    }

    public function onQuit(PlayerQuitEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pT = $this->pT[$n];

        $this->level->set($n, $pT->s_level);
        $this->skil->set($n, $pT->s_skil);
        $this->mana->set($n, $pT->s_mana);
        $this->coin->set($n, $pT->s_coin);
        $this->strage->set($n, $pT->s_strage);
        $this->data->set($n, $pT->s_data);
        $this->level->save();
        $this->skil->save();
        $this->mana->save();
        $this->coin->save();
        $this->data->save();

        switch ($ev->getQuitReason()) {
            case "Internal server error":
                $ev->setQuitMessage("§cInternal Server Error<<" . $n . "\n鯖主に連絡してください");
                break;
            case "client disconnect":
                $ev->setQuitMessage("§eQuit<<" . $n);
                break;
            default:
                $ev->setQuitMessage("§cUnknow[" . $ev->getQuitReason() . "§c]<<" . $n);

        }
    }

    public function onBreak(BlockBreakEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pT = $this->pT[$n];

        if ($p->getlevel()->getName() == self::lobby) {
            $p->addActionBarMessage("§4Hey! §rSorry, but you can't break that block here.");
            $ev->setCancelled();
            $pT->sendLog("ブロックの破壊をキャンセルしました");
        } else {
            $pT->onBreak($ev->getBlock());
        }
    }

    public function onPlace(BlockPlaceEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
        $pT = $this->pT[$n];

        if ($p->getlevel()->getName() == self::lobby) {
            $p->addActionBarMessage("§4Hey! §rSorry, but you can't place that block here.");
            $ev->setCancelled();
            $pT->sendLog("ブロックの設置をキャンセルしました");
        } else {
            $pT->onBreak($ev->getBlock());
        }
    }

    public function onTransaction(InventoryTransactionEvent $ev)
    {
        $tr = $ev->getTransaction();
        $inve = $tr->getInventories();
        $cansel = false;

        foreach ($inve as $inv) {
            foreach ($tr->getActions() as $action) {
                if ($action instanceof SlotChangeAction) {
                    if ($action->getInventory() instanceof DoubleChestInventory) {
                        $p = $action->getInventory()->getViewers();
                        $item = $action->getInventory()->getItem($action->getSlot());

                        if ($action->getInventory()->getItem($action->getSlot())->getId() != 0) {
                            //chestから出したとき
                        } else {
                            //chestに入れたとき
                        }

                        var_dump("c" . $action->getSlot() . "-" . $action->getInventory()->getItem($action->getSlot())->getId());


                    } else {
                        var_dump($action->getSlot() . "-" . $action->getInventory()->getItem($action->getSlot())->getId());
                        switch ($action->getSlot()) {
                            case 19;
                                $cansel = true;
                                $n = $action->getInventory()->getItem(35)->getName();
                                if ($this->pT[$n]->s_tempopen != "StackStrage") {
                                    $this->pT[$n]->s_tempopen = "StackStrage";
                                    ChestGuiManager::sendGui($this->pT[$n], "StackStrage");
                                }
                                break;

                            case 28:
                                $cansel = true;
                                $n = $action->getInventory()->getItem(35)->getName();
                                if ($this->pT[$n]->s_open != "dust") {
                                    $this->pT[$n]->s_open = "dust";
                                    ChestGuiManager::sendGui($this->pT[$n], "ゴミ箱");
                                }
                                break;

                            case 35:
                                $n = $action->getInventory()->getItem(35)->getName();
                                $ev->setCancelled();

                                $pT = $this->pT[$n];

                                $this->level->set($n, $pT->s_level);
                                $this->skil->set($n, $pT->s_skil);
                                $this->mana->set($n, $pT->s_mana);
                                $this->coin->set($n, $pT->s_coin);
                                $this->strage->set($n, $pT->s_strage);
                                $this->data->set($n, $pT->s_data);
                                $this->level->save();
                                $this->skil->save();
                                $this->mana->save();
                                $this->coin->save();
                                $this->data->save();

                                $pT = new PlayerTask($pT->getPlayer());

                                $pT->s_level = $this->level->get($n);
                                $pT->s_skil = $this->skil->get($n);
                                $pT->s_mana = $this->mana->get($n);
                                $pT->s_coin = $this->coin->get($n);
                                $pT->s_strage = $this->strage->get($n);
                                $pT->s_data = $this->data->get($n);

                                $this->pT[$n] = $pT;
                                $pT->sendLog("plyaerデータを所得しました");

                                break;

                            case 0:
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                            case 6:
                            case 7:
                            case 8:
                                break;

                            default:
                                $cansel = true;
                                break;
                        }
                    }
                }
            }
        }
        if ($cansel)
        {
            $ev->setCancelled();
        }
    }

    public function onOpen(InventoryOpenEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();
    }

    public function onClose(InventoryCloseEvent $ev)
    {
        $this->pT[$ev->getPlayer()->getName()]->s_open = false;
        ChestGuiManager::onClose($ev);
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
     * @return mixed
     */
    static public function getpT($n)
    {
        $main = self::$main;
        return $main->pT[$n];
    }

    /**
     * @return mixed
     */
    static public function getMain()
    {
        return self::$main;
    }
}
