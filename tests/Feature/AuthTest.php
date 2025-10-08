<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $userData = [
            'name' => 'Prakash test',
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/user/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'name',
                        'email',
                    ],
                ]); 

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    /** @test */
    public function registration_requires_valid_data()
    {
        $response = $this->postJson('/api/user/register', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [],
                    'error' => [
                        'name',
                        'email',
                        'password',
                    ],
                 ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $password = 'password';
        $user = User::factory()->create([
            'email' => 'dummy@gmail.com',
            'password' => bcrypt($password),
        ]);
        
        $loginData = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/auth/login', $loginData);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'access_token',
                        'token_type',
                        'user_details' => [
                            'name',
                            'email',
                        ]
                    ]
                ]);

        $this->assertAuthenticatedAs($user);
    }

     /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $loginData = [
            'email' => 'dummy@gmail.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(401)
                ->assertExactJson([
                    "status" => false,
                    "message"=> "Unauthorized",
                    "data" => [],
                    "error" => "Given email or password are not matched in our system"
                ]);
    }
}
