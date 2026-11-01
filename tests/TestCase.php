<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use LogicException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        // Stop before RefreshDatabase can touch a persistent workshop database.
        if ($app['config']->get('database.default') !== 'sqlite'
            || $app['config']->get('database.connections.sqlite.database') !== ':memory:') {
            throw new LogicException('Workshop tests require in-memory SQLite. Clear cached config and check phpunit.xml.');
        }

        return $app;
    }
}
