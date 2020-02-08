<?php

namespace App\Helpers\Func;

class Func {

    public static function array_has_dupes($array) {
        if(is_string($array)) {
            return false;
        }
        return count($array) !== count(array_unique($array));
    }
}
