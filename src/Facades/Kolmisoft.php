<?php

namespace CODEIQBV\Kolmisoft\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CODEIQBV\Kolmisoft\Kolmisoft
 */
class Kolmisoft extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CODEIQBV\Kolmisoft\Kolmisoft::class;
    }
}
