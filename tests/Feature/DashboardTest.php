<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        User::factory()->count(3)->create();
        $category = Category::create(['name' => 'Electronics', 'status' => true]);
        $inactiveCategory = Category::create(['name' => 'Books', 'status' => false]);
        Product::create([
            'name' => 'Headphones',
            'category_id' => $category->id,
            'price' => 49.99,
            'description' => 'Wireless headphones.',
            'status' => true,
        ]);
        Product::create([
            'name' => 'Inactive Book',
            'category_id' => $inactiveCategory->id,
            'price' => 10,
            'description' => 'An inactive product.',
            'status' => false,
        ]);
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response
            ->assertOk()
            ->assertSee('"total_users":4', false)
            ->assertSee('"total_categories":1', false)
            ->assertSee('"total_products":1', false);
    }
}
