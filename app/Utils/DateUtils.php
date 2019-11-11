<?php

namespace App\Utils;

use DateTime;
use Illuminate\Support\Facades\Date;

class DateUtils
{

    public static function convertDataDataBase($dataParam)
    {
        if (empty($dataParam)) {
            return null;
        }

        $data = explode(" ", $dataParam);

        list($day, $month, $year) = explode('/', $data[0]);

        $date = (new Date($year . '-' . $month . '-' . $day))->format('Y-m-d');

        if(count($data) >= 2){
           list($hour, $minutes) = explode(":", $data[1]);
           $date =  (new DateTime($year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minutes))->format('Y-m-d H:i');
        }

        return $date;
    }

    public static function convertDataToBR($data, $time = false)
    {

        if (empty($data)) {
            return null;
        }

        $format = ($time == false ? 'd/m/Y' : 'd/m/Y H:i');

        return date($format, strtotime($data));
    }

    public static function diffTime($data1, $data2, $type = 'default'){

        $dt1 = new DateTime($data1);
        $dt2 = new DateTime($data2);
        $diff = $dt1->diff($dt2);

        switch($type){
            case 'hour':
               $result = $diff->h + ($diff->d * 24);
               return $result;
            break;
            case 'minutes':
                $result = $diff->i + ($diff->h * 60);
                return $result;
            break;
            default:
                return $diff;
            break;
        }

    }

    public static function calcDowntime($hrDown, $hrUp)
    {
        $date = new DateTime($hrUp);
        $compare = $date->diff(new DateTime($hrDown));
        $downtime = $compare->h . ':' . $compare->i . ':' . $compare->s;

        return $downtime;
    }

    public static function calcWorkDowntime($horaup, $horadown, $tempInds)
    {

        //tempo  do expediente em minutos
        $ini_exp = 510; // inicio
        $fim_exp = 1050; // fim
        $total_exp = $fim_exp - $ini_exp;

        //separando data de hora
        $horacaiu = explode(' ', $horadown);
        $horavoltou = explode(' ', $horaup);

        //separando data, mes, ano
        $datacaiu = explode("-", $horacaiu[0]);
        $datavoltou = explode("-", $horavoltou[0]);

        // fragmentando horas, min, 00:00:0seg
        $horacaiuH = explode(":", $horacaiu[1]);
        $horavoltouH = explode(":", $horavoltou[1]);
        $tempIndsH = explode(":", $tempInds);

        // pegando o valor de horas e multiplicando por 60 pra converter em minutos
        $horacaiuMin = $horacaiuH[0] * 60;
        $horavoltouMin = $horavoltouH[0] * 60;
        $tempIndsMin = $tempIndsH[0] * 60;

        // somando o a conversão em minutos com os minutos restantes da hora
        $totalminC = $horacaiuMin + $horacaiuH[1];
        $totalminV = $horavoltouMin + $horavoltouH[1];
        $totaltempindsMIN = $tempIndsMin + $tempIndsH[1];

        //intervalo entre um exediente e outro
        $intervalo = 840; // são 14:00:00
        if ($totaltempindsMIN < 1440) {
            if ($totalminC >= $ini_exp and $totalminC <= $fim_exp) { // O bloco abaixo é executado quando o link cai durante o expediente.
                if ($totalminV >= $ini_exp and $totalminV <= $fim_exp) { // O bloco abaixo é executado quando o link volta durante o expediente.
                    if ($totaltempindsMIN >= $intervalo) { // o bloco abaixo é executado quando o link cai durante o expediente e volta durante o expediente do outro dia
                        $subtempo = $totaltempindsMIN - $intervalo;
                        $conv_hora = floor($subtempo / 60);
                        $resto = $subtempo % 60;
                        $total = $conv_hora . ':' . $resto . ':00';
                        return $total;
                    } else { // esse bloco é executado quando o link cai durante o expediente e volta durante o mesmo expediente
                        $subtempo = $totalminV - $totalminC;
                        $conv_hora = floor($subtempo / 60);
                        $resto = $subtempo % 60;
                        $total = $conv_hora . ':' . $resto . ':00';
                        return $total;
                    }
                } else { // o bloco abaixo é executado quando o link cai durante o expediente e volta fora o expediente
                    $totalminV = $fim_exp;
                    $subtempo = $totalminV - $totalminC;
                    $conv_hora = floor($subtempo / 60);
                    $resto = $subtempo % 60;
                    $total = $conv_hora . ':' . $resto . ':00';
                    return $total;
                }
            } else { // esse bloco é executado quando o link cai fora do exediente
                if ($totalminV >= $ini_exp and $totalminV < $fim_exp) { // esse bloco é executado quando o link cai fora do expediente e volta durante o expediente
                    $totalminC = $ini_exp;
                    $subtempo = $totalminV - $totalminC;
                    $conv_hora = floor($subtempo / 60);
                    $resto = $subtempo % 60;
                    $total = $conv_hora . ':' . $resto . ':00';
                    return $total;
                } else { // esse bloco é executado quando o link cai fora do expediente e volta fora do expediente
                    if ($totalminC >= $fim_exp and $totalminV <= $ini_exp) {
                        $total = '00:00:00';
                        return $total;
                    } else {
                        if ($totalminC > 360 and $totalminV < 480) {
                            $total = '00:00:00';
                            return $total;
                        } else {
                            $total = '9:00:00';
                            return $total;
                        }
                    }
                }
            }
        } else { // esse bloco é executado quando o tempo de indisponibilidade é maior que 24 horas.
            $quebraDC = explode("-", $horacaiu[0]);
            list($anoC, $mesC, $diaC) = $quebraDC;
            $quebraDV = explode(" ", $horaup);
            list($dataV, $horaV) = $quebraDV;
            $datasp = explode("-", $dataV);
            list($anoV, $mesV, $diaV) = $datasp;

            //converte para segundos
            $segC = mktime(00, 00, 00, $mesC, $diaC, $anoC);
            $segV = mktime(00, 00, 00, $mesV, $diaV, $anoV);
            $dif = $segV - $segC;
            $totaldias = (int) floor($dif / (60 * 60 * 24));

            if ($totaldias == 2) {
                $totaldias = $totaldias - 1;
            } else {
                if ($totaldias == 1) {
                    $total = "9:00:00";
                    return $total;
                    return;
                } else {
                    $totaldias = $totaldias - 2;
                }
            }

            $totaldias = $totaldias * $total_exp;
            $pri_dia = $totalminC <= $ini_exp ? $pri_dia = $total_exp : $pri_dia = $fim_exp - $totalminC;
            $ult_dia = $totalminV >= $fim_exp ? $ult_dia = $total_exp : $ult_dia = $totalminV - $ini_exp;
            $totalmin = $totaldias + $pri_dia + $ult_dia;
            $coverteHora = floor($totalmin / 60);
            $pegaresto = $totalmin % 60;
            $total = $coverteHora . ':' . $pegaresto . ':00';

            return $total;
        }
    }
}
