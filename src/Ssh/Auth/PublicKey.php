<?php
namespace Ssh\Auth;

use Ssh\Client;
use Ssh\Traits\ValidatorTrait;

class PublicKey extends Auth {

	use ValidatorTrait;

	/**
	 * @var string
	 */
	protected $pubKeyFile;

	/**
	 * @var string
	 */
	protected $privateKeyFile;

	/**
	 * @var string
	 */
	protected $passphrase;

	/**
	 * @param string $user
	 * @param string $pubKeyFile
	 * @param string $privateKeyFile
	 * @param string $passphrase
	 */
	public function __construct($user, $pubKeyFile = '', $privateKeyFile = '', $passphrase = '') {
		$this->setUser($user)->setPassphrase($passphrase);
		if ($pubKeyFile) {
			$this->setPubKeyFile($pubKeyFile);
		}
		if ($privateKeyFile) {
			$this->setPrivateKeyFile($privateKeyFile);
		}
	}

	/**
	 * Set public key file
	 *
	 * @param string $file
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setPubKeyFile($file) {
		if (file_exists($file) && is_file($file)) {
			$this->pubKeyFile = $file;
		} else {
			throw new \Exception('Public key file not exists or it is not a file');
		}
		return $this;
	}

	/**
	 * Set private key file
	 *
	 * @param string $file
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setPrivateKeyFile($file) {
		if (file_exists($file) && is_file($file)) {
			$this->privateKeyFile = $file;
		} else {
			throw new \Exception('Public key file not exists or it is not a file');
		}
		return $this;
	}

	/**
	 * @param string $passphrase
	 *
	 * @return $this
	 */
	public function setPassphrase($passphrase) {
		if ($this->isValidString($passphrase)) {
			$this->passphrase = $passphrase;
		}
		return $this;
	}

	/**
	 * @param Client $client
	 *
	 * @throws \RuntimeException
	 *
	 * @return mixed
	 */
	public function authenticate(Client $client) {
		if (!@ssh2_auth_pubkey_file(
			$client->getConnection(),
			$this->getUser(),
			$this->pubKeyFile,
			$this->privateKeyFile,
			$this->passphrase
		)) {
			throw new \RuntimeException('Authentication Failed');
		}
		parent::authenticate($client);
	}
}