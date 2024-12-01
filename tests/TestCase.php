<?php

namespace CODEIQBV\Kolmisoft\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use CODEIQBV\Kolmisoft\KolmisoftServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            KolmisoftServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('kolmisoft.api_url', 'http://example.com/billing');
        config()->set('kolmisoft.username', 'test_user');
        config()->set('kolmisoft.password', 'test_pass');
        config()->set('kolmisoft.auth_key', 'test_key');
        config()->set('kolmisoft.use_hash', true);
    }
}
