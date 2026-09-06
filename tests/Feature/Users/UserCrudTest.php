<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_visit_the_users_index(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('users.index'));

        $response->assertOk();
    }

    public function test_authenticated_users_can_visit_the_create_and_edit_pages(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('users.create'))->assertOk();
        $this->get(route('users.edit', $user))->assertOk();
    }

    public function test_authenticated_users_can_create_update_and_delete_users(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $this->post(route('users.store'), [
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
        ]);

        $user = User::where('email', 'alice@example.com')->firstOrFail();

        $this->put(route('users.update', $user), [
            'name' => 'Alice Updated',
            'email' => 'alice.updated@example.com',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Alice Updated',
            'email' => 'alice.updated@example.com',
        ]);

        $this->delete(route('users.destroy', $user))->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
