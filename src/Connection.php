<?php

namespace Almajiro\Ssh;

use Almajiro\Ssh\Exceptions\AuthorizationFailedException;
use Almajiro\Ssh\Exceptions\SshException;

class Connection
{
    /** @var string */
    private $username;
    /** @var string */
    private $hostname;
    /** @var int */
    private $port;

    private $usePassword = null;
    private $password = null;
    private $publicKeyPath = null;
    private $privateKeyPath = null;

    private $isConnected;
    private $connection;

    public function __construct(string $hostname, string $username)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->port = 22;
    }

    public function usePrivateKey(string $privateKeyPath, string $publicKeyPath)
    {
        $this->usePassword = false;
        $this->privateKeyPath = $privateKeyPath;
        $this->publicKeyPath = $publicKeyPath;
    }

    public function usePassword(string $password)
    {
        $this->usePassword = true;
        $this->password = $password;
    }

    public function connect()
    {
        $this->connection = @ssh2_connect($this->hostname, $this->port);

        if ($this->connection === false) {
            throw SshException::unableToConnect();
        }

        if (is_null($this->usePassword)) {
            throw AuthorizationFailedException::noCredentials();
        }

        if ($this->usePassword) {
            $this->authorizeViaPassword();
        } else {
            $this->authorizeViaPrivateKey();
        }

        $this->isConnected = true;
    }

    public function disconnect()
    {
        return !($this->isConnected = !ssh2_disconnect($this->connection));
    }

    public function isConnected()
    {
        return $this->isConnected;
    }

    public function command(string $command)
    {
        return new Command($this, $command);
    }

    public function filesystem()
    {
        return new FileSystem($this);
    }

    public function getConnection()
    {
        if (!$this->isConnected) {
            throw SshException::noConnection();
        }

        return $this->connection;
    }

    private function authorizeViaPassword()
    {
        if (@ssh2_auth_password($this->connection, $this->username, $this->password)) {
            return true;
        }

        throw AuthorizationFailedException::invalidPassword();
    }

    private function authorizeViaPrivateKey()
    {
        if (@ssh2_auth_pubkey_file($this->connection, $this->username, $this->publicKeyPath, $this->privateKeyPath)) {
            return true;
        }

        throw AuthorizationFailedException::invalidPrivateKey();
    }
}
