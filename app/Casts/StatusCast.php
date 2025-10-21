<?php 
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Enums\StatusType;
use InvalidArgumentException;
class StatusCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     *
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return StatusType::from($value);
    }
    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     *
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof StatusType) {
            return $value->value;
        }

        // If it's a string, try to convert it to StatusType enum
        if (is_string($value)) {
            try {
                return StatusType::from($value)->value;
            } catch (\ValueError $e) {
                throw new InvalidArgumentException('The given value is not a valid StatusType.');
            }
        }

        throw new InvalidArgumentException('The given value is not a valid StatusType.');
    }
}

