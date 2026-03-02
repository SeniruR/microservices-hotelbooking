<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        // In Docker, the runtime APP_URL is set to http://localhost:8080/bookings (gateway path).
        // PHPUnit's request builder uses app.url as a base, which would prefix all test requests
        // with /bookings and cause false 404s. Force a root URL for tests.
        $_ENV['APP_URL'] = 'http://localhost';
        $_SERVER['APP_URL'] = 'http://localhost';
        putenv('APP_URL=http://localhost');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
