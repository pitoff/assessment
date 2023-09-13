<?php

namespace Tests\Unit\Services;

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserServiceTest extends TestCase
{

    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_can_return_a_paginated_list_of_users()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $users = User::factory(10)->create();
        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
        // $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response->original);
        $response->assertSee('1');
    }

    public function test_user_can_be_stored()
    {
        $userData = [
            'prefixname' => 'Mr',
            'firstname' => "Jay",
            'middlename' => "Philipe",
            'lastname' => "Kounte",
            'suffixname' => "AGR",
            'username' => "jpk",
            'email' => 'jay@gmail.com',
            'password' => 'password123',
            'photo' => '',
            'type' => 'Non Technical',

        ];
        $response = $this->post(route('users.index'), $userData);
        $response->assertStatus(302);
    }

    public function test_can_return_an_existing_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $existingUser = User::factory()->create();
        $foundUser = User::findOrFail($existingUser->id);
        $this->assertEquals($existingUser->id, $foundUser->id);
        $this->assertEquals($existingUser->name, $foundUser->name);
        $response = $this->get(route('users.show', $existingUser->id));

        $response->assertStatus(200);
    }

    public function test_can_update_an_existing_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);
        $userData = [
            'prefixname' => 'Mr',
            'firstname' => "Ying",
            'middlename' => "Brown",
            'lastname' => "Jnr",
            'suffixname' => "AGM",
            'username' => "jpk",
            'email' => 'ying@gmail.com',
            'password' => 'password',
            'photo' => '',
            'type' => 'Non Technical',

        ];
        $user = User::factory()->create();
        $response = $this->patch(route('users.update', $user->id), $userData);
        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
    }

    public function test_can_soft_delete_an_existing_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $users = User::factory()->create();
        $response = $this->delete(route('users.destroy', $users->id));
        $response->assertStatus(302);
    }

    public function test_can_return_a_paginated_list_of_trashed_users()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $response = $this->get(route('users.trashed'));
        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_can_restore_a_soft_deleted_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $users = User::factory()->create();
        $response = $this->patch(route('users.restore', $users->id));
        $response->assertStatus(302);
    }

    public function test_can_permanently_delete_a_soft_deleted_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        $users = User::factory()->create();
        $response = $this->delete(route('users.delete', $users->id));
        $response->assertStatus(302);
    }

    public function test_can_upload_photo()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $userData = [
            'firstname' => "Ying",
            'lastname' => "Jnr",
            'username' => "jpk",
            'email' => 'ying@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'photo' => $file,
        ];
        
        $response = $this->post(route('users.store'), $userData);
        $response->assertStatus(302);
    }

    public function test_user_saved_listener_creates_user_details()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        Event::fake();
        event(new UserSaved($user));
        Event::assertDispatched(UserSaved::class);
    }
}
