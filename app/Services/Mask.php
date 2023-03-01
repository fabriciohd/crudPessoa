<?php

namespace App\Services;

class Mask{

    public static function format($val, $type)
    {
        $mask = '';
        $maskared = '';
        $k = 0;

        switch($type){
            case 'cpf' : $mask = '###.###.###-##';break;
            case 'cnpj' : $mask = '##.###.###/####-##';break;
            case 'cep' : $mask = '#####-###';break;
            case 'phone' : $mask = (strlen($val) == 11) ? '(##) # ####-####' : '(##) ####-####';break;
        }

        for($i = 0; $i<=strlen($mask)-1; $i++){
            if($mask[$i] == '#'){
                if(isset($val[$k]))
                $maskared .= $val[$k++];
            }
            else{
                if(isset($mask[$i]))
                $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}
