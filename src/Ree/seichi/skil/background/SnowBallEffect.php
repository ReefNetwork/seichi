<?php


namespace Ree\seichi\skil\background;


use pocketmine\level\particle\SnowballPoofParticle;
use pocketmine\level\Position;

class SnowBallEffect
{
	private const CLASSNAME = "SnowBallEffect";

	public const NAME = "雪玉";

	/**
	 * @param Position $pos
	 */
	public static function onRun(Position $pos): void
	{
		$particle = new SnowballPoofParticle($pos->asVector3());
		$pos->getLevel()->addParticle($particle);
		return;
	}

	/**
	 * @return string
	 */
	public static function getClassName(): string
	{
		return self::CLASSNAME;
	}

	/**
	 * @return string
	 */
	public static function getName(): string
	{
		return self::NAME;
	}
}