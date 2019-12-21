<?php


namespace Ree\seichi\skil\background;


use pocketmine\level\Position;

class BreakEffect
{
	private const CLASSNAME = "BreakEffect";

	public const NAME = "Default";

	/**
	 * @param Position $pos
	 */
	public static function onRun(Position $pos): void
	{
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