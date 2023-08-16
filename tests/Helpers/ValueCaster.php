<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;

/**
 * Helps casting values
 * Uses Laravel's HasAttributes trait to do the hard work
 */
class ValueCaster
{
    use HasAttributes;

    public function __construct()
    {
        $this->dateFormat = 'Y-m-d H:i:s';
    }


    /**
     * Required to properly use HasAttributes trait
     * @internal
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Required to properly use HasAttributes trait
     * @internal
     */
    public function getModel() {
        return null;
    }

    public function cast($value, $type)
    {
        $this->casts = ['cast' => $type];
        return $this->castAttribute('cast', $value);
    }
}
