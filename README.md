ssh2
====

Library which provide you ability execute remote commands like in terminal

With this library you can execute any commands using ssh2 protocol.

####Usage

- Password authentication

```php

<?php

use Ssh\Client;
use Ssh\Auth\Password;

$auth = new Password('username', 'password');
$client = new Client('host_name_or_ip');

try {
  $client->connect()->authenticate($auth);
  echo $client->exec('pwd');
} catch (\RuntimeException $e) {
  echo $e->getMessage();
}

```
- Public key authentication

```php

<?php

use Ssh\Client;
use Ssh\Auth\PublicKey;

$auth = new PublicKey('username', 'path to public key', 'path to private key', 'passphrase if set');
$client = new Client('host_name_or_ip');

try {
  $client->connect()->authenticate($auth);
  echo $client->exec('pwd');
} catch (\RuntimeException $e) {
  echo $e->getMessage();
}

```
- Command chain

```php

<?php

use Ssh\Client;
use Ssh\Auth\PublicKey;
use Ssh\Command\Result;
use Ssh\Command\Chain;

$auth = new PublicKey('username', 'path to public key', 'path to private key', 'passphrase if set');
$client = new Client('host_name_or_ip');

try {
  $client->connect()->authenticate($auth);
  echo $client
              ->chain()
              ->exec('pgrep node', function (Result $result, Chain $chain) {
                $result = $result->getResult();
                if ($result && is_numeric($result)) {
                  $chain->stopChain();
                }
              })
              ->exec('/usr/local/bin/node ~/server.js > ~/node_server.log &');
} catch (\RuntimeException $e) {
  echo $e->getMessage();
}

```
- With ssh config file

```php

<?php

use Ssh\Config\Configuration;
use Ssh\Client;

$configuration = new Configuration('path to ssh config file like a /home/username/.ssh/config');
$client = new Client($configuration->getHost('hostname'));

//  now if you need authenticates with password (PasswordAuthentication set to yes) you need to set password
$client->getAuth()->setPassword('xxxxx');

//  else if you set IdentityFile or PubkeyAuthentication is yes or not set PubkeyAuthentication
//  trying to find currenct user public key and private key files into ~/.ssh/ directory, if keys not exists
//  you need authenticates with methods above, if keys exists you can connect and authenticate

try {
  $client->connect()->authenticate();
  echo $client->exec('pwd');
} catch (\RuntimeException $e) {
  echo $e->getMessage();
}

```

- Shell

 With shell you can working like in terminal
 
```php

```
