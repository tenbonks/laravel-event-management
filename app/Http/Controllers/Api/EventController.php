<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller implements HasMiddleware
{

    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public static function middleware(): array
    {

        // All functions other than index and are protected with authentication

        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        Gate::authorize('viewAny', Event::class);

        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate(10)
        );
    }


    /**
     * Store a newly created resource in storage.<br>
     * Returns errors if fails validation<br>
     * Returns the newly created Event if passes validation
     */
    public function store(Request $request, EventRequest $eventRequest)
    {
        $event = Event::create([
            ...$eventRequest->validated(),
            'user_id' => $request->user()->id,
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage<br>
     * return error messages if validation FAILS<br>
     * return the resource, and a success message if validation PASSES.
     */
    public function update(EventRequest $request, Event $event)
    {

    /**
     * Below are two ways of using a defined Gate, they achieve the same thing.
     * Gate::authorize is a simplified way of authorizing a method, it will return a 403 and a standard message.
    */

//        if (Gate::denies('update-event', $event)) {
//            abort(403, 'You are not authorized to update this event.');
//        }

        Gate::authorize('update-event', $event);

        $event->update(
            $request->validated()
        );

        return new EventResource($this->loadRelationships($event));
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
