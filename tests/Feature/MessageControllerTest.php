<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching messages for authenticated user.
     */
    public function testFetchMessagesForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Message::factory()->count(3)->create(['sender_id' => $user->id]);

        $response = $this->getJson('/api/messages');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new message.
     */
    public function testCreateMessage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $messageData = [
            'receiver_id' => User::factory()->create()->id,
            'content' => $this->faker->sentence,
            'title' => $this->faker->jobTitle,
            'file' => UploadedFile::fake()->image('test.png')->size(100),
        ];

        $response = $this->postJson('/api/messages', $messageData);
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific message.
     */
    public function testViewMessage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);

        $response = $this->getJson("/api/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a message.
     */
    public function testUpdateMessage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);

        $updatedContent = $this->faker->sentence;

        $response = $this->putJson("/api/messages/{$message->id}", ['content' => $updatedContent]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a message.
     */
    public function testDeleteMessage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);

        $response = $this->deleteJson("/api/messages/{$message->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    /**
     * Test unauthorized access to a message.
     */
    public function testUnauthorizedAccessToMessage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUserMessage = Message::factory()->create(['sender_id' => User::factory()->create(), 'receiver_id' => User::factory()->create()]);

        $response = $this->getJson("/api/messages/{$otherUserMessage->id}");

        $response->assertStatus(403);
    }
}
