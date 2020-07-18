<?php

namespace Almajiro\Ssh\Exceptions;

use Exception;

class AuthorizationFailedException extends Exception
{
    public static function noCredentials()
    {
        return new self('Invalid Authorization Method');
    }

    public static function invalidPassword()
    {
        return new self('Failed to authorize via password');
    }

    public static function invalidPrivateKey()
    {
        return new self('Failed to authorize via Key-pair');
    }
}
