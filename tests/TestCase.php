<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Illuminate\Foundation\Testing\LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->setLocale('fr');
    }
}
