<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Models\Details;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserDetails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $user = $event->user;
        if($user){
            Details::create([
                "key" => $user->full_name,
                "value" => $user->middle_initial,
                "icon" => $user->avatar,
                "status" => $user->prefixname == "Mr" ? "Male" : "Female",
                "type" => "bio",
                "user_id" => $user->id
            ]); 
        }
    }
}
