<?php
namespace Ssh\Auth;

use Ssh\Client;

class Password extends Auth {

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @param string $user
	 * @param string $password
	 */
	public function __construct($user = '', $password = '') {
		$this
			->setUser($user)
			->setPassword($password);
	}

	/**
	 * @param string $password
	 *
	 * @return $this
	 */
	public function setPassword($password) {
		if (!empty($password) && is_string($password)) {
			$this->password = $password;
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param Client $client
	 * @throws \RuntimeException
	 *
	 * @return mixed
	 */
	public function authenticate(Client $client) {
		if (!@ssh2_auth_password($client->getConnection(), $this->getUser(), $this->getPassword())) {
			throw new \RuntimeException('Authentication Failed');
		}
		parent::authenticate($client);
	}
}