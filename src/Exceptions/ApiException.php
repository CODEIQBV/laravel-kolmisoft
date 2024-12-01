<?php

namespace CODEIQBV\Kolmisoft\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * @return self
     */
    public static function incorrectHash(): self
    {
        return new self("Incorrect hash provided.");
    }

    /**
     * @return self
     */
    public static function userNotFound(): self
    {
        return new self("User was not found.");
    }

    /**
     * @return self
     */
    public static function featureDisabled(): self
    {
        return new self("Feature is disabled.");
    }
} 