<?php

namespace App\Math;

class ValueComparator
{
    public static function isBiggerThanNull(string $value1): bool
    {
        return bccomp($value1, 0) === 1;
    }
}