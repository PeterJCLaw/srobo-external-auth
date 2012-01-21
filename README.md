This is an implementation of a single-sign-on solution, originally for use
by [Student Robotics](https://www.studentrobotics.org/).

## Setup
To set up a copy of SSO, you'll need to:

* Create a public/private key pair for the client to use (you can use the `client/mkkeypair.sh` script)
* Create a server configuration that's aware of your client:
 * Create a `config.inc.php` under `server/etc/`, which can be copied from the `config.default.php` in the same location.
 * Choose an AuthProvider; the JSON provider is probably simplest.
  * If it's a File based AuthProvider, you'll need to create users/groups files for it, also under /etc/.
 * Copy your public key into `server/etc/keys/`.
 * Further instructions are in `config.default.php`.
* Make your client use the matching private key, and set the url to the server.

## Example Client
There is an example client under `example/`, which demonstrates how the SSOClient class can be used.
