<?php
namespace Ssh\Config;

use Ssh\Auth\AuthInterface;
use Ssh\Auth\Password;
use Ssh\Auth\PublicKey;
use Ssh\Helper\Posix;

/**
 * Class HostConfiguration
 * @package Ssh
 *
 * @method getHostname
 * @method getUser
 * @method getCompression
 * @method getPreferredAuthentifications
 */
class HostConfiguration {

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var array
	 */
	protected $configuration;

	/**
	 * @param string $host
	 * @param array  $configuration
	 */
	public function __construct($host, array $configuration) {
		$this->host = $host;
		$this->configuration = $configuration;
	}

	/**
	 * @return string
	 */
	public function getIdentityFile() {
		if ($this->hasParam('IdentityFile')) {
			$file = $this->configuration['IdentityFile'];
			if ($file[0] == '~') {
				try {
					return str_replace('~', Posix::getUserHomeDir(), $file);
				} catch (\Exception $e) {
					return '';
				}
			}
			return $file;
		}
		return '';
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->hasParam('Port') ? (int) $this->configuration['Port'] : 22;
	}

	/**
	 * @return string
	 */
	public function getPasswordAuthentication() {
		return $this->hasParam('PasswordAuthentication') ? $this->configuration['PasswordAuthentication'] : 'no';
	}

	/**
	 * @return string
	 */
	public function getPubkeyAuthentication() {
		return $this->hasParam('PubkeyAuthentication') ? $this->configuration['PubkeyAuthentication'] : 'no';
	}

	/**
	 * @return AuthInterface
	 * @throws \RuntimeException
	 */
	public function getAuth() {
		if ($this->getPasswordAuthentication() == 'yes') {
			return new Password($this->getUser());
		} else if (('' != $pubKeyFile = $this->getIdentityFile()) || $this->getPubkeyAuthentication() == 'yes' || !$this->hasParam('PubkeyAuthentication')) {
			if (!$pubKeyFile) {
				$pubKeyFile = Posix::getUserHomeDir() . DIRECTORY_SEPARATOR . '.ssh/id_rsa.pub';
			}
			if (@file_exists($pubKeyFile)) {
				$privateKey = substr($pubKeyFile, 0, strrpos($pubKeyFile, DIRECTORY_SEPARATOR)) . DIRECTORY_SEPARATOR . 'id_rsa';
				if (file_exists($privateKey)) {
					return new PublicKey($this->getUser(), $pubKeyFile, $privateKey);
				}
			}
		}
		throw new \RuntimeException('Can not define AuthInterface');
	}

	/**
	 * @param $param
	 *
	 * @return bool
	 */
	public function hasParam($param) {
		return isset($this->configuration[$param]);
	}

	/**
	 * @param $method
	 * @param $params
	 *
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public function __call($method, $params) {
		if (0 == ($pos = strpos($method, 'get'))) {
			$parameter = substr($method, 3);
			if ($this->hasParam($parameter)) {
				return $this->configuration[$parameter];
			}
			$this->parameterNotFoundException($parameter);
		}
		throw new \RuntimeException(sprintf('Method `%s` does not exists', $method));
	}

	protected function parameterNotFoundException($parameter) {
		throw new \RuntimeException(sprintf("Parameter `%s` not found in %s host config", $parameter, $this->host));
	}
}