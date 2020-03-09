<?php

namespace Ree\seichi\sqlite;


interface ISqliteHelper
{
	/**
	 * ISqliteHelper constructor.
	 * @param string $db
	 */
	public function __construct(string $db);

	/**
	 * @param string $xuid
	 * @param string $name
	 * @param int $level
	 * @param int $xp
	 * @param array $skill
	 * @param string $nowSkill
	 * @param int $skillPoint
	 * @param float $mana
	 * @param float $coin
	 * @param int $gatya
	 * @return bool
	 */
	public function create(string $xuid, string $name, int $level = 0, int $xp = 0, array $skill = ['Skil'], string $nowSkill = 'Skil', int $skillPoint = 0, float $mana = 0, float $coin = 10, int $gatya = 10): bool ;

	/**
	 * @param string $xuid
	 * @return bool
	 */
	public function isExists(string $xuid): bool ;

	/**
	 * @param string $xuid
	 * @param string $name
	 * @return bool
	 */
	public function setName(string $xuid, string $name): bool ;

	/**
	 * @param string $xuid
	 * @return string|null
	 */
	public function getName(string $xuid): ?string ;

	/**
	 * @param string $xuid
	 * @param int $level
	 * @return bool
	 */
	public function setLevel(string $xuid, int $level): bool ;

	/**
	 * @param string $xuid
	 * @return int
	 */
	public function getLevel(string $xuid): ?int ;

	/**
	 * @param string $xuid
	 * @param array $skil
	 * @return bool
	 */
	public function setSkill(string $xuid, array $skil): bool ;

	/**
	 * @param string $xuid
	 * @return array
	 */
	public function getSkill(string $xuid): ?array ;

	/**
	 * @param string $xuid
	 * @param string $skill
	 * @return bool
	 */
	public function setNowSkill(string $xuid, string $skill): bool ;

	/**
	 * @param string $xuid
	 * @return string|null
	 */
	public function getNowSkill(string $xuid): ?string ;

	/**
	 * @param string $xuid
	 * @param int $point
	 * @return bool
	 */
	public function setSkillPoint(string $xuid, int $point): bool ;

	/**
	 * @param string $xuid
	 * @return int
	 */
	public function getSkillPoint(string $xuid): ?int ;

	/**
	 * @param string $xuid
	 * @param float $mana
	 * @return bool
	 */
	public function setMana(string $xuid, float $mana): bool ;

	/**
	 * @param string $xuid
	 * @return float
	 */
	public function getMana(string $xuid): ?float ;

	/**
	 * @param string $xuid
	 * @param string $coin
	 * @return bool
	 */
	public function setCoin(string $xuid,string $coin): bool;

	/**
	 * @param string $xuid
	 * @return float
	 */
	public function getCoin(string $xuid): ?float ;

	/**
	 * @param string $xuid
	 * @param int $xp
	 * @return bool
	 */
	public function setExperience(string $xuid, int $xp): bool ;

	/**
	 * @param string $xuid
	 * @return int
	 */
	public function getExperience(string $xuid): ?int ;

	/**
	 * @param string $xuid
	 * @param int $gatya
	 * @return bool
	 */
	public function setGatya(string $xuid, int $gatya): bool ;

	/**
	 * @param string $xuid
	 * @return int
	 */
	public function getGatya(string $xuid): ?int ;

	/**
	 * @return array
	 */
	public function getAllData(): array;

//	/**
//	 * @param string $xuid
//	 * @param string $name
//	 * @return bool
//	 */
//	public function setXuid(string $xuid, string $name): bool ;

	/**
	 * @param string $name
	 * @return string
	 */
	public function getXuid(string $name): ?string ;

	public function begin(): void ;

	public function commit(): void ;

	public function rollBack(): void ;

	public function close(): void ;
}