<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VoterRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_can_register_and_redundant_queries_are_avoided()
    {
        // Seed data
        DB::table('voter_models')->insert([
            'idNum' => 12345,
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'Middle',
            'password' => bcrypt('password'),
            'college_init' => 'ICS',
        ]);

        $data = [
            'idNum' => 12345,
            'email' => 'john.doe@example.com',
            'college_init' => 'ICS',
            'password' => 'newpassword',
        ];

        $response = $this->postJson('/api/voter_create', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('voter_acct_models', [
            'idNum' => 12345,
            'email' => 'john.doe@example.com',
            'fname' => 'John',
            'lname' => 'Doe',
        ]);
        $this->assertDatabaseMissing('voter_models', ['idNum' => 12345]);
    }
}
