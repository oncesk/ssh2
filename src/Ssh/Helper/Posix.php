<?php
namespace Ssh\Helper;

/**
 * Class Posix
 * @package Ssh\Helper
 */
class Posix {

	/**
	 * @var string
	 */
	private static $user;

	/**
	 * @var array
	 */
	private static $userInfo;

	/**
	 * @return string
	 */
	public static function getUser() {
		if (static::$user) {
			return static::$user;
		}
		static::notLoadException();
		return static::$user = posix_getlogin();
	}

	/**
	 * @return string
	 */
	public static function getUserHomeDir() {
		$config = static::getUserInfo();
		return $config['dir'];
	}

	/**
	 * @return array
	 * @throws \RuntimeException
	 */
	public static function getUserInfo() {
		if (static::$userInfo) {
			return static::$userInfo;
		}
		static::notLoadException();
		static::$userInfo = posix_getpwnam(static::getUser());
		if (static::$userInfo === false) {
			throw new \RuntimeException('Can not fetch user info');
		}
		return static::$userInfo;
	}

	/**
	 * @return bool
	 */
	public static function isLoaded() {
		return extension_loaded('posix');
	}

	/**
	 * @throws \RuntimeException
	 */
	private static function notLoadException() {
		if (!static::isLoaded()) {
			throw new \RuntimeException('Posix extension not loaded');
		}
	}
}