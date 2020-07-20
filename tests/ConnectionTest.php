<?php

namespace Tests;

use Almajiro\Ssh\Connection;
use Almajiro\Ssh\Exceptions\AuthorizationFailedException;
use Almajiro\Ssh\Exceptions\SshException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    private $password = 'password';

    private function getConnection(string $hostname = 'server', string $username = 'root')
    {
        return new Connection($hostname, $username);
    }

    public function testConnectionViaPassword()
    {
        $connection = $this->getConnection();
        $connection->usePassword($this->password);
        $connection->connect();

        $this->assertTrue($connection->isConnected());
    }

    public function testConnectionViaPrivateKey()
    {
        $connection = $this->getConnection();
        $connection->usePrivateKey(
            '/var/www/tests/keys/id_rsa',
            '/var/www/tests/keys/id_rsa.pub'
        );
        $connection->connect();

        $this->assertTrue($connection->isConnected());
    }

    public function testAuthorizationErrorViaPassword()
    {
        $this->expectException(AuthorizationFailedException::class);

        $connection = $this->getConnection();
        $connection->usePassword('invalid_password');
        $connection->connect();
    }

    public function testAuthorizationErrorViaPrivateKey()
    {
        $this->expectException(AuthorizationFailedException::class);

        $connection = $this->getConnection();
        $connection->usePrivateKey(
            '/var/www/tests/keys/invalid_id_rsa',
            '/var/www/tests/keys/invalid_id_rsa.pub'
        );
        $connection->connect();
    }

    public function testConnectionError()
    {
        $this->expectException(SshException::class);

        $connection = $this->getConnection('dummy_server');
        $connection->usePassword($this->password);
        $connection->connect();
    }

    public function testNoCredentialsError()
    {
        $this->expectException(AuthorizationFailedException::class);

        $connection = $this->getConnection();
        $connection->connect();
    }

    public function testWhenNotConnectedThrowsException()
    {
        $this->expectException(SshException::class);

        $connection = $this->getConnection();
        $connection->usePassword($this->password);

        $connection->getConnection();
    }

    public function testDisconnect()
    {
        $connection = $this->getConnection();
        $connection->usePassword($this->password);
        $connection->connect();

        $this->assertTrue($connection->disconnect());
    }
}
