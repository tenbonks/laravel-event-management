<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate(10)
        );
    }

//    protected function shouldIncludeRelation(string $relation): bool
//    {
//        $include = request()->query('include');
//
//        if (!$include) {
//            // Make include return false if it's null
//            return false;
//        }
//
//        // split and sanitize the include query
//        $relations = array_map('trim' ,explode(',', $include));
//
//        // Return true if in array,
//        return in_array($relation, $relations);
//    }


    /**
     * Store a newly created resource in storage.<br>
     * Returns errors if fails validation<br>
     * Returns the newly created Event if passes validation
     */
    public function store(EventRequest $request)
    {

        $event = Event::create([
            ...$request->validated(),
            'user_id' => 1
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
