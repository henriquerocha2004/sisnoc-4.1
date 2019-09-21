<?php

namespace App\Utils;

class Utils
{

    public static function getDataOfArraysByComparing($firtArrayOfObject, $secondArrayOfObject)
    {
        $delete = array_diff($firtArrayOfObject, $secondArrayOfObject);
        $insert = array_diff($secondArrayOfObject, $firtArrayOfObject);
        $update = array_intersect($firtArrayOfObject, $secondArrayOfObject);
        return [
            'delete' => $delete,
            'insert' => $insert,
            'update' => $update
        ];
    }
}
