<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Policies\AttendeePolicy;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(Attendee::class, AttendeePolicy::class);

//        /**
//         * Allow a user to update an event if they are the user that created it
//         **/
//        Gate::define('update-event', function ($user, Event $event) {
//            return $user->id === $event->user_id;
//        });
//
//
//        /**
//         *  If the authenticated user is the event owner, or added the attendee to the event, allow them to delete the attendee
//         */
//        Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
//            return $user->id === $event->user_id || $user->id === $attendee->user_id;
//        });
    }
}
