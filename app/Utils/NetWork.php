<?php
namespace App\Utils;

class NetWork
{
    public static $ip;
    public static $os;
    public static $resultPing;
    public static $link;
    public static $retorno = [
        'retorno' => null,
        'result'  => null,
        'ip'      => null,
        'link'    => null
    ];
    public static function testePing($ip, $link)
    {
        self::$ip = $ip;
        self::$link = $link;
        self::checkOS();

        if (self::$os) {
            exec("ping " . self::$ip, $output, $return_var);
        } else {
            exec("ping -w1 -i 0.2 -c 4 " . self::$ip, $output, $return_var);
        }
        self::$resultPing = $output;
        self::checkResult();
        return json_encode(self::$retorno);
    }
    public static function checkOS()
    {
        // self::$os = preg_match('/(Win32)|(Win64)/', $_SERVER['SERVER_SOFTWARE'] ?? $_SERVER['OS']);
        self::$os = preg_match('/(Win32)|(Win64)/', $_SERVER['SERVER_SOFTWARE'] ?? true);
    }
    private static function checkResult()
    {
        if (count(self::$resultPing)) {

            $Saida = array_map('trim', self::$resultPing);

            $ICMP1 = (preg_match('/Resposta/', $Saida[2]) ? true : (preg_match('/64 bytes/', $Saida[1]) ? true : false));
            $ICMP2 = (preg_match('/Resposta/', $Saida[3]) ? true : (preg_match('/64 bytes/', $Saida[2]) ? true : false));
            $ICMP3 = (preg_match('/Resposta/', $Saida[4]) ? true : (preg_match('/64 bytes/', $Saida[3]) ? true : false));
            $ICMP4 = (preg_match('/Resposta/', $Saida[4]) ? true : (preg_match('/64 bytes/', $Saida[4]) ? true : false));

            $pingCheck = "";

            foreach ($Saida as $Result) :
                $pingCheck = utf8_encode($pingCheck . "\n" . $Result);
            endforeach;

            if ($ICMP1 == true and $ICMP2 == true and $ICMP3 == true and $ICMP4 == true) {
                self::$retorno['retorno'] = true;
                self::$retorno['result'] = $pingCheck;
                self::$retorno['ip'] = self::$ip;
                self::$retorno['link'] = self::$link;
            }
            elseif($ICMP1 == FALSE AND $ICMP2 == FALSE AND $ICMP3 == FALSE AND $ICMP4 == FALSE)
            {
                self::$retorno['retorno'] = false;
                self::$retorno['result'] = $pingCheck;
                self::$retorno['ip'] = self::$ip;
                self::$retorno['link'] = self::$link;
            }else
            {
                self::$retorno['retorno'] = false;
                self::$retorno['result'] = $pingCheck;
                self::$retorno['ip'] = self::$ip;
                self::$retorno['link'] = self::$link;
                self::$retorno['msg'] = "Perda de pacotes";
            }
        }
        else
        {
            self::$retorno['msg'] = "O IP informado não é válido.";
        }
    }
}
