<?php

namespace Almajiro\Ssh\Exceptions;

use Exception;

class SshException extends Exception
{
    static public function noConnection()
    {
        return new self("No connection is established.");
    }

    static public function unableToConnect()
    {
        return new self("Unable to connect server.");
    }
}

