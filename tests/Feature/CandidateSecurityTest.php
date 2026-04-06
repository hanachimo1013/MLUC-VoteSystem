<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CandidateSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_svg_upload_is_blocked()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['access-admin']);

        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(\'XSS\')"></svg>';
        $file = UploadedFile::fake()->create('malicious.svg', 1, 'image/svg+xml');

        $response = $this->postJson('/api/create_candidate', [
            'lname' => 'Doe',
            'fname' => 'John',
            'mname' => 'Middle',
            'college_init' => 'CCS',
            'election_id' => 1,
            'partylist_id' => 1,
            'position_id' => 1,
            'image' => $file,
            'description' => 'Test candidate description'
        ]);

        // This is expected to fail with validation error after the fix
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }
}
