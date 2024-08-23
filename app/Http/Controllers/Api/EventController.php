<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EventResource::collection(Event::with('user', 'attendees')->get());
    }

    /**
     * Store a newly created resource in storage.<br>
     * Returns errors if fails validation<br>
     * Returns the newly created Event if passes validation
     */
    public function store(EventRequest $request)
    {

        // I do have EventRequest setup, but the two uses of it here are different, and
        // I am not sure how to use the Requests with two different set of rules


        $event = Event::create([
            ...$request->validated(),
            'user_id' => 1
        ]);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // The EventResource has user and attendees set to be included in the JsonResponse when loaded
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage<br>
     * return error messages if validation FAILS<br>
     * return the resource, and a success message if validation PASSES.
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update(
            $request->validated()
        );

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage, and return a 204 status,
     * which a typical practice when deleting a resource.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        // php 8^ you can use 'name parameters', before 8 you would need to do the below return like this...
        // return response('', 204);
        return response(status: 204);
    }
}
