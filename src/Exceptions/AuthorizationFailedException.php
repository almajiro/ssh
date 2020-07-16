<?php

namespace Almajiro\Ssh\Exceptions;

use Exception;

class AuthorizationFailedException extends Exception
{
    static public function noCredentials()
    {
        return new self("Invalid Authorization Method");
    }

    static public function invalidPassword()
    {
        return new self("Failed to authorize via password");
    }

    static public function invalidPrivateKey()
    {
        return new self("Failed to authorize via Key-pair");
    }
}

