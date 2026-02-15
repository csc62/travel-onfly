<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/travel-orders', [
            'destination' => 'Paris',
            'departure_date' => '2026-03-01',
            'return_date' => '2026-03-10',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('travel_orders', [
            'user_id' => $user->id,
            'destination' => 'Paris',
        ]);
    }

    public function test_user_can_list_orders()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        TravelOrder::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/travel-orders');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_user_can_view_order_by_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $order = TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/travel-orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $order->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_admin_can_update_order_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->patchJson("/api/travel-orders/{$order->id}/status", [
            'status' => 'aprovado'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('travel_orders', [
            'id' => $order->id,
            'status' => 'aprovado'
        ]);
    }
}
