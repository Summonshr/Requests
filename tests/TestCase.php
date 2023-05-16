<?php

namespace Summonshr\Requests\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Summonshr\Requests\RequestsServiceProvider;

abstract class TestCase extends TestbenchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RequestsServiceProvider::class,
        ];
    }
}
