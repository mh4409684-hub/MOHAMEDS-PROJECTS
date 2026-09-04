<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_ticket_with_mass_assignment(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Hardware Maintenance']);

        $ticket = Ticket::create([
            'ticket_number' => 'GST-000123',
            'subject' => 'Scanner not working',
            'description' => 'The scanner on desk 3 fails to power on.',
            'category_id' => $category->id,
            'department_id' => 1,
            'sla_policy_id' => null,
            'priority' => 'high',
            'status' => 'new',
            'source' => 'portal',
            'requester_id' => $user->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'ticket_number' => 'GST-000123',
            'subject' => 'Scanner not working',
            'requester_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_list_only_their_own_tickets(): void
    {
        $category = Category::create(['name' => 'Hardware Maintenance']);
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        Ticket::create([
            'ticket_number' => 'GST-000001',
            'subject' => 'First user ticket',
            'description' => 'Issue for first user.',
            'category_id' => $category->id,
            'department_id' => 1,
            'sla_policy_id' => null,
            'priority' => 'medium',
            'status' => 'new',
            'source' => 'portal',
            'requester_id' => $firstUser->id,
        ]);

        Ticket::create([
            'ticket_number' => 'GST-000002',
            'subject' => 'Second user ticket',
            'description' => 'Issue for second user.',
            'category_id' => $category->id,
            'department_id' => 1,
            'sla_policy_id' => null,
            'priority' => 'low',
            'status' => 'new',
            'source' => 'portal',
            'requester_id' => $secondUser->id,
        ]);

        $this->actingAs($firstUser)
            ->get('/tickets')
            ->assertOk()
            ->assertSee('First user ticket')
            ->assertDontSee('Second user ticket');
    }
}
