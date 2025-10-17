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

        throw new InvalidArgumentException('The given value is not a valid StatusType .');
    }
}

