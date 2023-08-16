<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ramsey\Uuid\UuidInterface;

class Uuid implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        return \Ramsey\Uuid\Uuid::fromString($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  UuidInterface  $value
     * @param  array  $attributes
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        if ($value) {
            return $value->toString();
        } else {
            return null;
        }
    }
}
