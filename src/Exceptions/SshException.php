<?php

namespace Almajiro\Ssh\Exceptions;

use Exception;

class SshException extends Exception
{
    public static function noConnection()
    {
        return new self('No connection is established.');
    }

    public static function unableToConnect()
    {
        return new self('Unable to connect server.');
    }
}
