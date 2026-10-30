<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_token_and_user_for_valid_login(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'token',
                'token_type',
                'expires_in',
                'user' => ['id', 'name', 'email', 'role'],
            ])
            ->assertJsonPath('user.role', 'admin');

        $this->resetJwtAuth();

        $this->withToken($response->json('token'))
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_returns_401_for_invalid_login_credentials(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ])->assertUnauthorized()
            ->assertJsonPath('message', 'Email atau password tidak valid.');
    }

    public function test_returns_401_for_profile_request_without_a_token(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_logout_invalidates_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->json('token');

        $this->resetJwtAuth();
        $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();

        $this->resetJwtAuth();
        $this->withToken($token)->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_refresh_returns_a_new_token_and_invalidates_the_old_one(): void
    {
        $user = User::factory()->create();
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->json('token');

        $this->resetJwtAuth();
        $refreshResponse = $this->withToken($token)->postJson('/api/v1/auth/refresh');

        $refreshResponse->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'expires_in', 'user']);
        $this->assertNotSame($token, $refreshResponse->json('token'));

        $this->resetJwtAuth();
        $this->withToken($token)->getJson('/api/v1/auth/me')->assertUnauthorized();

        $this->resetJwtAuth();
        $newTokenResponse = $this->withToken($refreshResponse->json('token'))->getJson('/api/v1/auth/me');
        $newTokenResponse->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    private function resetJwtAuth(): void
    {
        app('tymon.jwt')->unsetToken();
        Auth::forgetGuards();
    }
}
