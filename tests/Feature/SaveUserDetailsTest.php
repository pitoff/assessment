<?php

namespace Tests\Feature;

use App\Listeners\SaveUserDetails as ListenersSaveUserDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SaveUserDetailsTest extends TestCase
{
 
    use RefreshDatabase;

    public function test_user_saved_event_triggers_listener()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertEquals(auth()->user()->id, $user->id);

        User::factory()->create();

        // Mock the event listener to prevent it from running during testing
        Queue::fake();
        Event::fake([
            UserSaved::class => ListenersSaveUserDetails::class,
        ]);

        // Perform the action that should trigger the event (e.g., user registration)
        $userData = [
            'firstname' => "Ying",
            'lastname' => "Jnr",
            'username' => "jpk",
            'email' => 'ying@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'photo' => '',
        ];
        $response = $this->post(route('users.store'), $userData);
        Event::assertDispatched(UserSaved::class);
    }
}
