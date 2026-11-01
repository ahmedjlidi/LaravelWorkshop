<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class WorkshopTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Reproducible dates, independent of the date of your workshop.
        $this->travelTo(\Illuminate\Support\Carbon::parse('2030-01-15 10:00:00', 'UTC'));
        $this->seed();
    }
}
