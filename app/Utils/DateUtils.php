<?php

namespace App\Utils;

use DateTime;

class DateUtils{

    public static function convertDataDataBase($dataParam){
        if(empty($dataParam)){
            return null;
        }

        $data = explode(" ", $dataParam);
        list($day, $month, $year) = explode('/', $data[0]);
        list($hour,$minutes) = explode(":", $data[1]);

        return (new DateTime($year . '-' . $month . '-' .$day . ' '.$hour . ':' . $minutes ))->format('Y-m-d H:i');
    }

    public static function convertDataToBR($data){
        return date('d/m/Y', strtotime($data));
    }


}
