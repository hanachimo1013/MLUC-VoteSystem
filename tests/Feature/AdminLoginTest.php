<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful admin login.
     *
     * @return void
     */
    public function test_successful_admin_login()
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/adminLogin', [
            'email' => 'admin@example.com',
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user',
                     'token'
                 ]);
    }

    /**
     * Test admin login with invalid password.
     *
     * @return void
     */
    public function test_admin_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/adminLogin', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'errors' => 'This account is not existing or check your inputs!'
                 ]);
    }

    /**
     * Test admin login with non-existent account.
     *
     * @return void
     */
    public function test_admin_login_with_non_existent_account()
    {
        $response = $this->postJson('/api/adminLogin', [
            'email' => 'nonexistent@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'errors' => 'This account is not existing or check your inputs!'
                 ]);
    }

    /**
     * Test admin login with missing fields.
     *
     * @return void
     */
    public function test_admin_login_with_missing_fields()
    {
        $response = $this->postJson('/api/adminLogin', [
            // Missing email and password
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);

        $response = $this->postJson('/api/adminLogin', [
            'email' => 'admin@example.com',
            // Missing password
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);

        $response = $this->postJson('/api/adminLogin', [
            'password' => 'secret123',
            // Missing email
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
