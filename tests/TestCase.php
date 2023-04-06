<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Throwable;
use App\Http\Helper\SubscriberHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        if (!SubscriberHelper::getApiKeyFromDb()) {
            error_log('Error: Please insert mailerlite api key into the database first before running tests.');
            exit;
        }

        $response = $this->postJson('/api/subscriber', ['email' => 'testing-subscriber@example.com', 'name' => 'Testing', 'country' => 'Indonesia']);
    }
}
