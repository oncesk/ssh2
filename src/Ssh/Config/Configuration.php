<?php
namespace Ssh\Config;

use Ssh\Config\ConfigFileParser;
use Ssh\Config\HostConfiguration;

/**
 * Class Configuration
 * @package Ssh
 */
class Configuration {

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @var string
	 */
	protected $fileContent;

	/**
	 * @var HostConfiguration[]
	 */
	protected $hostConfiguration = array();

	/**
	 * @param $file
	 * @throws \Exception
	 */
	public function __construct($file) {
		if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
			throw new \Exception('Configuration file `' . $file . '` is not readable');
		}
		$this->file = $file;
		$this->loadConfiguration();
		$this->parse();
	}

	/**
	 * @param string $name
	 *
	 * @return HostConfiguration
	 * @throws \RuntimeException
	 */
	public function getHost($name) {
		if ($this->hasHost($name)) {
			return $this->hostConfiguration[$name];
		}
		throw new \RuntimeException(sprintf("Host `%s` not exists", $name));
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasHost($name) {
		return isset($this->hostConfiguration[$name]);
	}

	/**
	 * Parse config file configuration
	 */
	protected function parse() {
		if (!$this->fileContent) {
			return;
		}
		foreach (ConfigFileParser::parse($this->fileContent) as $hostName => $parameters) {
			$this->hostConfiguration[$hostName] = $this->createHost($hostName, $parameters);
		}
	}

	/**
	 * @param string $host
	 * @param array  $config
	 *
	 * @return HostConfiguration
	 */
	protected function createHost($host, array $config) {
		return new HostConfiguration($host, $config);
	}

	/**
	 * Load config file content
	 */
	protected function loadConfiguration() {
		$this->fileContent = file_get_contents($this->file);
	}
}