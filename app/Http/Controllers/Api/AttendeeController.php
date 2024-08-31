<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{

    use CanLoadRelationships;

    private array $relations = ['user'];

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
    public function store(Request $request, Event $event)
    {

        // event_id is automatically set
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id,
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
        $attendee->delete();

        return response(status: 204);
    }
}
