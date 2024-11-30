<?php

namespace CODEIQBV\Kolmisoft\Exceptions;

use Exception;

class ApiException extends Exception
{
    public static function incorrectHash()
    {
        return new static("Incorrect hash provided.");
    }

    public static function userNotFound()
    {
        return new static("User was not found.");
    }

    public static function featureDisabled()
    {
        return new static("Feature is disabled.");
    }
} 