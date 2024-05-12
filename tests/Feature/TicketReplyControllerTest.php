<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TicketReplyControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching ticket replies for authorized user.
     */
    public function testFetchTicketRepliesForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        TicketReply::factory()->count(3)->create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/tickets/{$ticket->id}/ticket-replies");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new ticket reply.
     */
    public function testCreateTicketReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $replyData = [
            'ticket_id' => $ticket->id,
            'content' => fake()->sentence()
            // Add other necessary fields as per StoreTicketReplyRequest
        ];

        $response = $this->postJson("/api/tickets/{$ticket->id}/ticket-replies", $replyData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific ticket reply.
     */
    public function testViewTicketReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $ticketReply = TicketReply::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $response = $this->getJson("/api/ticket-replies/{$ticketReply->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a ticket reply.
     */
    public function testUpdateTicketReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $ticketReply = TicketReply::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $updatedData = [
            'file' => UploadedFile::fake()->create('test.pdf', 1000)
            // Provide updated data as per UpdateTicketReplyRequest
        ];

        $response = $this->patchJson("/api/ticket-replies/{$ticketReply->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a ticket reply.
     */
    public function testDeleteTicketReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $ticketReply = TicketReply::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $response = $this->deleteJson("/api/ticket-replies/{$ticketReply->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('ticket_replies', ['id' => $ticketReply->id]);
    }

    /**
     * Test unauthorized access to a ticket reply.
     */
    public function testUnauthorizedAccessToTicketReply()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherTicketReply = TicketReply::factory()->create();

        $response = $this->getJson("/api/ticket-replies/{$otherTicketReply->id}");

        $response->assertStatus(403);
    }
}
