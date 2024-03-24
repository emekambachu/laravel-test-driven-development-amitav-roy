<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_send_user_token_on_correct_credentials(): void
    {
        $user = User::factory()->create();
        $this->json('POST', '/api/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(200)->assertJson(function(AssertableJson $json) use ($user) {
            $json->has('token')
                ->where('user_name', $user->name)
                ->etc();
        });
    }

    public function test_return_error_on_incorrect_credentials(): void
    {
        $user = User::factory()->create();
        $this->json('POST', '/api/user/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ])->assertStatus(422)->assertJson(function(AssertableJson $json) {
            $json->has('errors')
                ->where('errors.email.0', 'The provided credentials are incorrect.')
                ->etc();
        });
    }
}
