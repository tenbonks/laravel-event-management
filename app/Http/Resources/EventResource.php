<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * For example, if you wanted to change how the data is outputted compared to how it is in the model, this file would be a place to do that
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // All model date for event is available within $this

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'start_time'  => $this->formatDate($this->start_time),
            'end_time'    => $this->formatDate($this->end_time),
            'venue'       => $this->venue,
            'location'    => $this->location,
            'user'        => new UserResource($this->whenLoaded('user')),
            'attendees'   => AttendeeResource::collection($this->whenLoaded('attendees')),
        ];
    }

    private function formatDate($date)
    {
        return date("Y-m-d H:i", strtotime($date));
    }
}
