<?php
namespace Ssh\Auth;

use Ssh\Client;

interface AuthInterface {

	/**
	 * @param string $user
	 *
	 * @return $this
	 */
	public function setUser($user);

	/**
	 * @return string
	 */
	public function getUser();

	/**
	 * @param Client $client
	 * @throws \RuntimeException
	 *
	 * @return mixed
	 */
	public function authenticate(Client $client);

	/**
	 * @param Client $client
	 *
	 * @return bool
	 */
	public function isAuthenticated(Client $client);
}