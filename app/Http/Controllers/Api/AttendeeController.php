<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class AttendeeController extends Controller implements HasMiddleware
{

    use CanLoadRelationships;

    private array $relations = ['user'];

    public static function middleware(): array
    {

        // Authorisation required on delete and store actions

        return [
            new Middleware('auth:sanctum', except: ['index', 'show', 'update']),
        ];
    }

    /**
     * Display attendees for a given event, paginated
     */
    public function index(Event $event)
    {

        // $relations variable is automatically passed to the loadRelationships method
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, Request $request)
    {

        // event_id is automatically set
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1,
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($this->loadRelationships($attendee))
        );
    }

    /**
     * Update has been removed from this controller due to it not being used.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        Gate::authorize('delete-attendee', [$event, $attendee]);

        $attendee->delete();

        return response(status: 204);
    }
}
