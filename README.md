This is an implementation of a an external authenticator solution,
originally for use by [Student Robotics](https://www.studentrobotics.org/).

## Setup
To set up a copy of External-Auth, you'll need to:

* Create a server configuration that's valid:
 * Create a `config.inc.php` under `server/etc/`, which can be copied from the `config.default.php` in the same location.
 * Create a public/private key pair for the server to use (you can use the `tools/mkkeypair.sh` script)
 * Choose an AuthProvider; the JSON provider is probably simplest.
  * If it's a File based AuthProvider, you'll need to create users/groups files for it, also under /etc/.
* Create a client configuration that's aware of your server:
 * Copy the server's public key into the client, and use this when constructing the SSOClient class.
* Make the server's sessions folder writable by PHP

## Example Client
There is an example client under `example/`, which demonstrates how the SSOClient class can be used.
This expects the server's public key to be available at `example/example_keys/external-auth.pub`
