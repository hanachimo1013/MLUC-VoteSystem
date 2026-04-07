<?php

namespace Tests\Feature;

use App\Models\VoterModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoterLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_can_login_with_valid_credentials()
    {
        $voter = VoterModel::create([
            'idNum' => 123456,
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'Smith',
            'college_init' => 'CBA',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/voterLogin', [
            'idNum' => 123456,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'voter' => ['id', 'idNum', 'fname', 'lname'],
            'token'
        ]);
    }

    public function test_voter_cannot_login_with_invalid_credentials()
    {
        $voter = VoterModel::create([
            'idNum' => 123456,
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'Smith',
            'college_init' => 'CBA',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/voterLogin', [
            'idNum' => 123456,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => 'Wrong inputs.Please try again or register your account.'
        ]);
    }

    public function test_voter_login_requires_idNum_and_password()
    {
        $response = $this->postJson('/api/voterLogin', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['idNum', 'password']);
    }

    public function test_voter_cannot_login_with_nonexistent_idNum()
    {
        $response = $this->postJson('/api/voterLogin', [
            'idNum' => 999999,
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => 'Wrong inputs.Please try again or register your account.'
        ]);
    }
}
