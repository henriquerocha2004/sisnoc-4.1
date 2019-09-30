<?php

namespace App\Utils;

class TextUtil
{

    public static function clearText($value): String
    {
        return str_replace(['(', ')', '-', '.', ' ', '/'], '', $value);
    }
}
