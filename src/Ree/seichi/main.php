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

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use Ree\seichi\sqlite\SqliteHelper;


class main extends PluginBase implements listener
{
	private static $main;

	public function onEnable()
	{
		echo "Reef_core >> loading now...\n";
		self::$main = $this;
		$config = new Config($this->getDataFolder() . "user_data.yml", Config::YAML);
		$keys = array_keys($config->getAll());
		foreach ($keys as $key) {
			$this->migrate($config->getAll()[$key], $key);
		}
		SqliteHelper::getInstance()->commit();
		echo "Reef_core >> Complete\n";
		$this->getServer()->shutdown();
	}

	public static function getMain() {
		return self::$main;
	}

	public function migrate(array $data, string $name): void {
		$db = SqliteHelper::getInstance();
		$db->create($name);
		$db->setLevel($name, $data["level"]);
		$db->setSkill($name, $data["skil"]);
		$db->setSkillPoint($name, $data["skilpoint"]);
		$db->setCoin($name, $data["coin"]);
		$db->setExperience($name, $data["experience"]);
		$db->setGatya($name, $data["gatya"]);
	}
}
