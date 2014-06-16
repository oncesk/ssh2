<?php
namespace Ssh\Auth;

use Ssh\Auth\AuthInterface;
use Ssh\Client;

/**
 * Class Base
 * @package Ssh\AuthInterface
 */
abstract class Auth implements AuthInterface {

	/**
	 * @var Client[]
	 */
	protected $authenticated = array();

	/**
	 * @var string
	 */
	protected $user;

	/**
	 * @param string $user
	 *
	 * @return $this
	 */
	public function setUser($user) {
		if (!empty($user) && is_string($user)) {
			$this->user = $user;
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param Client $client
	 *
	 * @throws \RuntimeException
	 *
	 * @return mixed
	 */
	public function authenticate(Client $client) {
		$this->authenticated[(int) $client->getConnection()] = $client;
		$client->authenticate($this);
	}


	/**
	 * @param Client $client
	 *
	 * @return bool
	 */
	public function isAuthenticated(Client $client) {
		return isset($this->authenticated[(int) $client->getConnection()]);
	}
}