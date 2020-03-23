<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace Bavfalcon9\Mavoric\Core\Utils\Math;

use function in_array;

class Facing{

	public const AXIS_Y = 0;
	public const AXIS_Z = 1;
	public const AXIS_X = 2;

	public const FLAG_AXIS_POSITIVE = 1;

	/* most significant 2 bits = axis, least significant bit = is positive direction */
	public const DOWN =   self::AXIS_Y << 1;
	public const UP =    (self::AXIS_Y << 1) | self::FLAG_AXIS_POSITIVE;
	public const NORTH =  self::AXIS_Z << 1;
	public const SOUTH = (self::AXIS_Z << 1) | self::FLAG_AXIS_POSITIVE;
	public const WEST =   self::AXIS_X << 1;
	public const EAST =  (self::AXIS_X << 1) | self::FLAG_AXIS_POSITIVE;

	public const ALL = [
		self::DOWN,
		self::UP,
		self::NORTH,
		self::SOUTH,
		self::WEST,
		self::EAST
	];

	public const HORIZONTAL = [
		self::NORTH,
		self::SOUTH,
		self::WEST,
		self::EAST
	];

	private const CLOCKWISE = [
		self::AXIS_Y => [
			self::NORTH => self::EAST,
			self::EAST => self::SOUTH,
			self::SOUTH => self::WEST,
			self::WEST => self::NORTH
		],
		self::AXIS_Z => [
			self::UP => self::EAST,
			self::EAST => self::DOWN,
			self::DOWN => self::WEST,
			self::WEST => self::UP
		],
		self::AXIS_X => [
			self::UP => self::NORTH,
			self::NORTH => self::DOWN,
			self::DOWN => self::SOUTH,
			self::SOUTH => self::UP
		]
	];

	/**
	 * Returns the axis of the given direction.
	 *
	 * @param int $direction
	 *
	 * @return int
	 */
	public static function axis(int $direction) : int{
		return $direction >> 1; //shift off positive/negative bit
	}

	/**
	 * Returns whether the direction is facing the positive of its axis.
	 *
	 * @param int $direction
	 *
	 * @return bool
	 */
	public static function isPositive(int $direction) : bool{
		return ($direction & self::FLAG_AXIS_POSITIVE) === self::FLAG_AXIS_POSITIVE;
	}

	/**
	 * Returns the opposite Facing of the specified one.
	 *
	 * @param int $direction 0-5 one of the Facing::* constants
	 *
	 * @return int
	 */
	public static function opposite(int $direction) : int{
		return $direction ^ self::FLAG_AXIS_POSITIVE;
	}

	/**
	 * Rotates the given direction around the axis.
	 *
	 * @param int  $direction
	 * @param int  $axis
	 * @param bool $clockwise
	 *
	 * @return int
	 * @throws \InvalidArgumentException if not possible to rotate $direction around $axis
	 */
	public static function rotate(int $direction, int $axis, bool $clockwise) : int{
		if(!isset(self::CLOCKWISE[$axis])){
			throw new \InvalidArgumentException("Invalid axis $axis");
		}
		if(!isset(self::CLOCKWISE[$axis][$direction])){
			throw new \InvalidArgumentException("Cannot rotate direction $direction around axis $axis");
		}

		$rotated = self::CLOCKWISE[$axis][$direction];
		return $clockwise ? $rotated : self::opposite($rotated);
	}

	/**
	 * @param int  $direction
	 * @param bool $clockwise
	 *
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public static function rotateY(int $direction, bool $clockwise) : int{
		return self::rotate($direction, self::AXIS_Y, $clockwise);
	}

	/**
	 * @param int  $direction
	 * @param bool $clockwise
	 *
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public static function rotateZ(int $direction, bool $clockwise) : int{
		return self::rotate($direction, self::AXIS_Z, $clockwise);
	}

	/**
	 * @param int  $direction
	 * @param bool $clockwise
	 *
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public static function rotateX(int $direction, bool $clockwise) : int{
		return self::rotate($direction, self::AXIS_X, $clockwise);
	}

	/**
	 * Validates the given integer as a Facing direction.
	 *
	 * @param int $facing
	 * @throws \InvalidArgumentException if the argument is not a valid Facing constant
	 */
	public static function validate(int $facing) : void{
		if(!in_array($facing, self::ALL, true)){
			throw new \InvalidArgumentException("Invalid direction $facing");
		}
	}
}