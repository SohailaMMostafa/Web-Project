<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\{UploadedFile as UploadedFileAlias};

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    private $hasFailed = false;

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->hasFailed) {
            echo "The test has failed!\n";
        } else {
            echo "The test has been performed successfully\n";
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_user_can_register()
    {
        $formData = [
            'full_name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'mobile_number' => '1234567890',
            'password' => 'password@1',
            'password_confirmation' => 'password@1',
            'date_of_birth' => '2000-01-01',
            'address' => '1234 Main St',
            'user_image' => UploadedFileAlias::fake()->image('profile.jpg'),
        ];

        try {
            $response = $this->post('/register', $formData);

            $response->assertCreated();

            $this->assertDatabaseHas('users', [
                'email' => 'john@example.com'
            ]);

            Mail::assertSent(WelcomeEmail::class, function ($mail) use ($formData) {
                return $mail->hasTo(env('ADMIN_MAIL'));
            });

            $response->assertJson([
                'status' => 'success',
                'message' => 'User registered successfully. Welcome email sent!',
            ]);
        } catch (Exception $e) {
            $this->hasFailed = true;
            throw $e;
        }
    }

    /** @test
     * @throws Exception
     */
    public function user_registration_fails_due_to_validation_errors()
    {
        $response = $this->json('POST', '/register', [
            'full_name' => 'John Doe',
            'username' => '',
            'email' => 'john.com',
            'mobile_number' => '1234567890',
            'password' => 'password@1',
            'password_confirmation' => 'password@1!',
            'date_of_birth' => '2000--01',
            'address' => '1234 Main St',
            'user_image' => UploadedFileAlias::fake()->image('profile.jpg'),
        ]);

        try{
            $response->assertStatus(422);
            $response->assertJson([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => [
                    'username' => ['The username field is required.'],
                    'email' => ['The email field must be a valid email address.'],
                    'date_of_birth' => ['The date of birth field must be a valid date.'],
                    'password' => ['The password field confirmation does not match.']
                ]
            ]);
        }catch (Exception $e){
            $this->hasFailed = true;
            throw $e;
        }
    }
    /** @test
     * @throws Exception
     */
    public function user_registration_fails_due_to_incorrect_password()
    {
        $response = $this->json('POST', '/register', [
            'full_name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'mobile_number' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
            'date_of_birth' => '2000-01-01',
            'address' => '1234 Main St',
            'user_image' => UploadedFileAlias::fake()->image('profile.jpg'),
        ]);

        try{
            $response->assertStatus(422);
            $response->assertJson([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => [
                    'password' => ['The password field format is invalid.']
                ]
            ]);
        }catch (Exception $e){
            $this->hasFailed = true;
            throw $e;
        }
    }
}
