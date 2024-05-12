<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\MessageReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MessageReplyControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching message replies for authorized user.
     */
    public function testFetchMessageRepliesForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);
        MessageReply::factory()->count(3)->create(['message_id' => $message->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/messages/{$message->id}/message-replies");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new message reply.
     */
    public function testCreateMessageReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);

        $replyData = [
            'message_id' => $message->id,
            'content' => fake()->sentence(),
            'file' => UploadedFile::fake()->create('test.sh', 1000),
            // Add other necessary fields as per StoreMessageReplyRequest
        ];

        $response = $this->postJson("/api/messages/{$message->id}/message-replies", $replyData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific message reply.
     */
    public function testViewMessageReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);
        $messageReply = MessageReply::factory()->create(['message_id' => $message->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/message-replies/{$messageReply->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a message reply.
     */
    public function testUpdateMessageReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);
        $messageReply = MessageReply::factory()->create(['message_id' => $message->id, 'user_id' => $user->id]);

        $updatedData = [
            'file' => UploadedFile::fake()->create('test.jpg', 1000)
            // Provide updated data as per UpdateMessageReplyRequest
        ];

        $response = $this->patchJson("/api/message-replies/{$messageReply->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a message reply.
     */
    public function testDeleteMessageReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $message = Message::factory()->create(['sender_id' => $user->id]);
        $messageReply = MessageReply::factory()->create(['message_id' => $message->id, 'user_id' => $user->id]);

        $response = $this->deleteJson("/api/message-replies/{$messageReply->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('message_replies', ['id' => $messageReply->id]);
    }

    /**
     * Test unauthorized access to a message reply.
     */
    public function testUnauthorizedAccessToMessageReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherMessageReply = MessageReply::factory()->create();

        $response = $this->getJson("/api/message-replies/{$otherMessageReply->id}");

        $response->assertStatus(403);
    }
}
