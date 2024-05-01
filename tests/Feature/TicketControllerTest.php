<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching tickets for authenticated user.
     */
    public function testFetchTicketsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Ticket::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new ticket.
     */
    public function testCreateTicket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticketData = [
            'title' => 'New Ticket',
            'description' => 'This is a new ticket.',
        ];

        $response = $this->postJson('/api/tickets', $ticketData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific ticket.
     */
    public function testViewTicket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a ticket.
     */
    public function testUpdateTicket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Ticket Title',
            'description' => 'Updated ticket description.',
        ];

        $response = $this->patchJson("/api/tickets/{$ticket->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a ticket.
     */
    public function testDeleteTicket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    /**
     * Test unauthorized access to a ticket.
     */
    public function testUnauthorizedAccessToTicket()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUserTicket = Ticket::factory()->create();

        $response = $this->getJson("/api/tickets/{$otherUserTicket->id}");

        $response->assertStatus(403);
    }
}
