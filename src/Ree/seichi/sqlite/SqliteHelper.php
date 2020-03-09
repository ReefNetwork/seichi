<?php

namespace Ree\seichi\sqlite;


use Ree\seichi\main;
use SQLite3;

class SqliteHelper implements ISqliteHelper
{
	/**
	 * @var SQLite3
	 */
	private static $db;

	/**
	 * @var ISqliteHelper
	 */
	private static $instance;

	/**
	 * @inheritDoc
	 */
	public static function getInstance(): ISqliteHelper
	{
		if (self::$instance === null) {
			self::$instance = new SqliteHelper(main::getMain()->getDataFolder() . '\data.db');
		}
		return self::$instance;
	}

	/**
	 * @inheritDoc
	 */
	public function __construct(string $db)
	{
		self::$db = new SQlite3($db);
		self::setTable();
		$this->begin();
	}

	/**
	 * @inheritDoc
	 */
	public function isExists(string $xuid): bool
	{
		$stmt = self::$db->prepare('SELECT * FROM data WHERE (xuid = :xuid )');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return !empty($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setLevel(string $xuid, int $level): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET level = :level WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':level', $level, SQLITE3_INTEGER);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getLevel(string $xuid): ?int
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT level FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setSkill(string $xuid, array $skill): bool
	{
		$skill = implode(',', $skill);
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET skill = :skill WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindParam(':skill', $skill, SQLITE3_TEXT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getSkill(string $xuid): ?array
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT skill FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return explode(',', current($stmt->execute()->fetchArray()));
	}

	/**
	 * @inheritDoc
	 */
	public function setSkillPoint(string $xuid, int $point): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET point = :point WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':point', $point, SQLITE3_INTEGER);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getSkillPoint(string $xuid): ?int
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT point FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setMana(string $xuid, float $mana): bool
	{
		$mana = round($mana, 2);
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET mana = :mana WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':mana', $mana, SQLITE3_FLOAT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getMana(string $xuid): ?float
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT mana FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setCoin(string $xuid, string $coin): bool
	{
		$coin = round($coin, 2);
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET coin = :coin WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':coin', $coin, SQLITE3_FLOAT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getCoin(string $xuid): ?float
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT coin FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setExperience(string $xuid, int $xp): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET xp = :xp WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':xp', $xp, SQLITE3_FLOAT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getExperience(string $xuid): ?int
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT xp FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setGatya(string $xuid, int $gatya): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET gatya = :gatya WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindValue(':gatya', $gatya, SQLITE3_FLOAT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getGatya(string $xuid): ?int
	{
		if (!$this->isExists($xuid)) return null;
		$stmt = self::$db->prepare('SELECT gatya FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function getAllData(): array
	{
		return self::$db->query('SELECT * FROM data')->fetchArray(SQLITE3_NUM);
	}

//	public function setXuid(string $xuid, string $xuid): bool
//	{
//		$name = strtolower($name);
//		$xuid = $this->getXuid($name);
//		if (!$this->isExists($name)) return false;
//		$stmt = self::$db->prepare('REPLACE INTO xuid FROM data WHERE xuid = :xuid ');
//		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
//		$stmt->execute();
//		return true;
//	}

	public function getXuid(string $name): ?string
	{
		$name = strtolower($name);
		$stmt = self::$db->prepare('SELECT xuid FROM data WHERE name = :name ');
		$stmt->bindParam(':name', $name, SQLITE3_TEXT);
		$array = $stmt->execute()->fetchArray();
		if (empty($array)) {
			return null;
		} else {
			return current($array);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function create(string $xuid, string $name, int $level = 0, int $xp = 0, array $skill = ['Skil'], string $nowSkill = 'Skil', int $skillPoint = 0, float $mana = 0, float $coin = 10, int $gatya = 10): bool
	{
		$name = strtolower($name);
		$skill = implode(',', $skill);
		if ($this->isExists($name)) return false;
		$stmt = self::$db->prepare('REPLACE INTO data VALUES (:xuid, :name, :level, :xp, :skill, :now, :point, :mana, :coin, :gatya)');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindParam(':name', $name, SQLITE3_TEXT);
		$stmt->bindValue(':level', $level, SQLITE3_INTEGER);
		$stmt->bindValue(':xp', $xp, SQLITE3_INTEGER);
		$stmt->bindParam(':skill', $skill, SQLITE3_TEXT);
		$stmt->bindParam(':now', $nowSkill, SQLITE3_TEXT);
		$stmt->bindValue(':point', $skillPoint, SQLITE3_INTEGER);
		$stmt->bindValue(':mana', $mana, SQLITE3_FLOAT);
		$stmt->bindValue(':coin', $coin, SQLITE3_FLOAT);
		$stmt->bindValue(':gatya', $gatya, SQLITE3_INTEGER);
		$stmt->execute();
		return true;
	}

	private static function setTable(): void
	{
		self::$db->exec('CREATE TABLE IF NOT EXISTS data (xuid TEXT NOT NULL PRIMARY KEY ,name TEXT NOT NULL,level NUMERIC NOT NULL,xp NUMERIC NOT NULL ,skill TEXT NOT NULL, nowSkill TEXT NOT NULL, point NUMERIC NOT NULL,mana NUMERIC NOT NULL ,coin NUMERIC NOT NULL ,gatya NUMERIC NOT NULL)');
	}

	public function close(): void
	{
		self::$db->close();
		self::$instance = null;
	}

	public function begin(): void
	{
		self::$db->exec('begin');
	}

	public function commit(): void
	{
		self::$db->exec('commit');
	}

	public function rollBack(): void
	{
		self::$db->exec('rollback');
	}

	/**
	 * @inheritDoc
	 */
	public function setNowSkill(string $xuid, string $skill): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET nowSKill = :skill WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindParam(':skill', $skill, SQLITE3_TEXT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getNowSkill(string $xuid): ?string
	{
		if (!$this->isExists($xuid)) return $name;
		$stmt = self::$db->prepare('SELECT nowSkill FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}

	/**
	 * @inheritDoc
	 */
	public function setName(string $xuid, string $name): bool
	{
		if (!$this->isExists($xuid)) return false;
		$stmt = self::$db->prepare('UPDATE data SET name = :name WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		$stmt->bindParam(':name', $name, SQLITE3_TEXT);
		$stmt->execute();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(string $xuid): ?string
	{
		if (!$this->isExists($xuid)) return $name;
		$stmt = self::$db->prepare('SELECT name FROM data WHERE xuid = :xuid ');
		$stmt->bindParam(':xuid', $xuid, SQLITE3_TEXT);
		return current($stmt->execute()->fetchArray());
	}
}