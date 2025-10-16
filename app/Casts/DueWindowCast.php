<?php 

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use stdClass;

class DueWindowCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) return null;
        $data = json_decode($value, true);
        // return a simple DTO-like object
        $obj = new stdClass();
        $obj->due_at = isset($data['due_at']) ? \Illuminate\Support\Carbon::parse($data['due_at']) : null;
        $obj->remind_before = $data['remind_before'] ?? null;
        return $obj;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        // Accept array or object or DTO
        if (is_null($value)) return null;
        if (is_object($value)) $value = (array) $value;
        return json_encode($value);
    }
}
