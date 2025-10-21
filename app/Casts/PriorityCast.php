<?php 

namespace App\Casts;
use App\Enums\PriorityType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class PriorityCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \App\Enums\PriorityType|null
     */
    public function get($model, string $key, $value, array $attributes): ?PriorityType
    {
        return $value !== null ? PriorityType::from($value) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \App\Enums\PriorityType|null|string  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof PriorityType) {
            return $value->value;
        }

        // If it's a string, try to convert it to PriorityType enum
        if (is_string($value)) {
            try {
                return PriorityType::from($value)->value;
            } catch (\ValueError $e) {
                throw new InvalidArgumentException('The given value is not a valid PriorityType.');
            }
        }

        throw new InvalidArgumentException('The given value is not an instance of PriorityType enum.');
    }
}