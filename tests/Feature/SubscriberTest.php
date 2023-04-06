<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriberTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test post subscriber using an existing email
     *
     * @return void
     */
    public function testExistingEmailPostSubscriber()
    {
        $response = $this->postJson('/api/subscriber', ['email' => 'testing-subscriber@example.com', 'name' => 'Testing', 'country' => 'Indonesia']);
        $response->assertStatus(400)
            ->assertJson(['message' => 'Subscriber already exists']);
    }

    /**
     * Test post subscriber using an invalid email
     *
     * @return void
     */
    public function testInvalidEmailPostSubscriber()
    {
        $response = $this->postJson('/api/subscriber', ['email' => 'test', 'name' => 'Testing', 'country' => 'Indonesia']);
        $response->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => [
                        "The email must be a valid email address."
                    ]
                ]
            ]);
    }

    /**
     * Test get subscriber
     *
     * @return void
     */
    public function testGetSubscriber()
    {
        $response = $this->getJson('/api/subscriber');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [[
                    "id",
                    "email",
                    "name",
                    "country",
                    "subscribe_date",
                    "subscribe_time"
                ]]
            ]);

    }

    /**
     * Test show subscriber
     *
     * @return void
     */
    public function testShowSubscriber()
    {
        $response = $this->getJson('/api/subscriber/testing-subscriber@example.com');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "country",
            ]);
    }


    /**
     * Test update subscriber
     *
     * @return void
     */
    public function testUpdateSubscriber()
    {
        $subscriber = $this->getJson('/api/subscriber/testing-subscriber@example.com');
        $response = $this->putJson('/api/subscriber/' . $subscriber->json()['id'], ['name' => 'Testing Updated', 'country' => 'Singapore']);
        $response
            ->assertStatus(200);
    }

    /**
     * Test delete subscriber
     *
     * @return void
     */
    public function testDeleteSubscriber()
    {
        $subscriber = $this->getJson('/api/subscriber/testing-subscriber@example.com');
        $response = $this->deleteJson('/api/subscriber/' . $subscriber->json()['id']);
        $response
            ->assertStatus(200);
    }
}
