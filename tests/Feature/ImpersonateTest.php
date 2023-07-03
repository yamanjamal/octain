<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ImpersonateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_impersonator_changes_the_user(): void
    {
        $impersonator = User::factory()->create();
        $impersonatee = User::factory()->create();

        $this->signIn($impersonator);

        $this->get('/api/me')
            ->assertStatus(200)
            ->assertJsonPath('user.id', $impersonator->id);

        $this->post('/api/impersonate/' . $impersonatee->id)->assertStatus(200);

        $this->get('/api/me')
            ->assertStatus(200)
            ->assertJsonPath('user.id', $impersonator->id);

        $this->delete('/api/impersonate')->assertStatus(200);

        $this->get('/api/me')
            ->assertStatus(200)
            ->assertJsonPath('user.id', $impersonator->id);
    }

    public function signIn(User $user = null)
    {
        $user ?? $user = User::factory()->create();

        $this->actingAs($user, 'web');
    }
}