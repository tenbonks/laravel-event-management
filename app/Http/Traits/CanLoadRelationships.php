<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{

    /**
     * helper  for loading relationships when building queries directly through a Model, or using Eloquent/Query builder
     *
     * ## Usage
     *
     * - Importing the trait
     *     - `use App\Http\Traits\CanLoadRelationships;`
     * - Using the trait (example)
     *     - `$query = $this->loadRelationships(Event::query(), ['user', 'attendees']);`
     */

    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {

        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn ($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        // split and sanitize the include query
        $relations = array_map('trim' ,explode(',', $include));

        // Return true if in array,
        return in_array($relation, $relations);
    }

}
