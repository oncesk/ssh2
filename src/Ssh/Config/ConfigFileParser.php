<?php
namespace Ssh\Config;

/**
 * Class ConfigFileParser
 * @package Ssh
 */
class ConfigFileParser {

	/**
	 * @param string $content
	 *
	 * @return array
	 */
	public static function parse($content) {
		$parsed = array();
		$currentHost = '';
		foreach (explode(PHP_EOL, $content) as $line) {
			if (empty($line) || $line[0] == '#') {
				continue;
			}
			if (preg_match('/^Host (.*)/', $line, $m)) {
				$currentHost = $m[1];
				$parsed[$currentHost] = array();
			} else if (preg_match('/(.*)=(.*)/', $line, $m) || preg_match('/([a-zA-Z0-9]+) (.*)/', $line, $m)) {
				$parsed[$currentHost][trim($m[1])] = trim($m[2]);
			}
		}
		return $parsed;
	}
}