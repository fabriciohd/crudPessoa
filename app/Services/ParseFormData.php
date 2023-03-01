<?php

namespace App\Services;
use Carbon\Carbon;

class ParseFormData
{

    public static function clearString($data)
    {
        if (!$data) return;

        return preg_replace('/[^A-Za-z0-9]/', '', $data);;
    }

    public static function dateFormat($data)
    {
        if (!$data) return;

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data)) {
            return $data;
        }

        $dataPart = explode('/', $data);

        if (!checkdate($dataPart[1], $dataPart[0], $dataPart[2])) {
            return;
        }


        return $dataPart[2] . "-" . $dataPart[1] . "-" . $dataPart[0];
    }

    public static function moneyFormat($value)
    {
        if (!$value) $value = 0;

        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return $value;
    }

}
