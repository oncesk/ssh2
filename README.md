ssh2
====

Library which provide you ability execute remote commands as in terminal

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
