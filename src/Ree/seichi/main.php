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
		$this->do(new Config($this->getDataFolder() . "user_data.yml", Config::YAML));
		echo "Reef_core >> Complete\n";
		$this->getServer()->shutdown();
	}

	public function onDisable()
	{
		SqliteHelper::getInstance()->commit();
		parent::onDisable();
	}

	public static function getMain() {
		return self::$main;
	}

	private function do(Config $config): void {
		$keys = array_keys($config->getAll());
		$count = count($keys);
		$progress = 0;
		$percent = 0;
		$time = microtime(true);
		foreach ($keys as $key) {
			$progress++;
			if (SqliteHelper::getInstance()->getXuid($key) === null) {
				$xuid = $this->getXuid($key);
				if (!$this->is_json($xuid)) {
					$this->migrate($config->get($key), $xuid, $key);
					echo 'データの引継ぎに成功しました name : '.$key.'  value: '.$xuid."\n";
				} else {
					$config->remove($key);
					$config->save();
					echo 'データの引継ぎに失敗しました name : '.$key.'  value: '.$xuid."\n";
				}
			} else {
				echo 'すでにデータが引き継がれています name : '.$key."\n";
			}
			$num = $progress / $count;
			if ($percent < floor($num * 100)) {
				$percent++;
				echo $percent.'% complete'. "\n";
			}
		}
		echo 'finish progress time : '.round(microtime(true) - $time);
	}

	private function migrate(array $data, string $xuid, string $name): void {
		$db = SqliteHelper::getInstance();
		$db->create($xuid, $name);
		$db->setLevel($xuid, $data["level"]);
		$db->setSkill($xuid, $data["skil"]);
		$db->setSkillPoint($xuid, $data["skilpoint"]);
		$db->setCoin($xuid, $data["coin"]);
		$db->setExperience($xuid, $data["experience"]);
		$db->setGatya($xuid, $data["gatya"]);
	}

	private function getXuid(string $name): ?string {
		$ch = curl_init("https://www.xboxapi.com/v2/xuid/" . rawurlencode($name));
		$headers = array('X-Auth: 5d26ee090a27aed817c96a25212eee294c88a426');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		$xuid = curl_exec( $ch ); # run!
		curl_close($ch);
		return $xuid;
	}

	private function is_json($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}
