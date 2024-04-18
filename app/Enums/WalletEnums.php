<?php

namespace App\Enums;

class WalletEnums
{
    const admin_wallet          = "admin wallet";
    const external_fare  = 'external fare';
    const internal_fare       = 'internal fare';
    const minimum_wallet = 'minimum wallet';
    const time_out   = 'time out';
    const admin_fare   = 'admin fare';


    public static function getName($value)
    {
        $constants = array_flip((new \ReflectionClass(self::class))->getConstants());

        return $constants[$value] ?? null;
    }

    public static function getId($name)
    {
        switch (true) {

            case $name == self::admin_wallet :
                return 1;
            
            case $name == self::external_fare :
                return 2;
                                
            case $name == self::internal_fare :
                return 3;
                            
            case $name == self::minimum_wallet :
                return 4;
                            
            case $name == self::time_out :
                return 5;

            case $name == self::admin_fare :
                return 6;
            
            default:
                return ;
        }

    }

    public static function getValue($name)
    {
        return defined('self::' . $name) ? constant('self::' . $name) : null;
    }

    public static function getValues()
    {
        return [
            self::admin_wallet,
            self::external_fare,
            self::internal_fare,
            self::minimum_wallet,
            self::time_out,
            self::admin_fare,
        ];
    }
}
