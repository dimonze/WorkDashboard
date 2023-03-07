<?php

namespace App\Command\Helpers\Parsers;

use DateTime;

class GeneralParser
{
    public static function strToDateObj($strDate): DateTime|null
    {
        return DateTime::createFromFormat('m/d/Y', $strDate) ? DateTime::createFromFormat('m/d/Y', $strDate) : null;
    }
    public static function removeCfaPrefix($string): string
    {
        $re = '/CFA Institute.* -> (.*)/m';
        preg_match($re, $string, $matches, PREG_OFFSET_CAPTURE, 0);
        try {
            $result = $matches[1][0];
        } catch (\Exception $exp){
            $result = $string;
        }
        return $result;
    }



}