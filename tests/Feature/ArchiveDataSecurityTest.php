<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArchiveDataSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_php_upload_is_blocked_in_archive_data()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['access-admin']);

        $phpContent = '<?php echo "RCE"; ?>';
        $file = UploadedFile::fake()->createWithContent('malicious.php', $phpContent);

        $response = $this->postJson('/api/archive_data', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    public function test_csv_upload_is_allowed_in_archive_data()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['access-admin']);

        $csvContent = 'id,name\n1,test';
        $file = UploadedFile::fake()->createWithContent('data.csv', $csvContent);

        $response = $this->postJson('/api/archive_data', [
            'file' => $file,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => 'The file has been archived!']);
    }
}
