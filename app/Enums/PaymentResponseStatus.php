<?php

namespace App\Enums;

class PaymentResponseStatus
{
    const success = 0;
    const failed = 100;
    const unauthorized = 1;


    public static function getName($value)
    {
        $constants = array_flip((new \ReflectionClass(self::class))->getConstants());

        return $constants[$value] ?? null;
    }

    public static function getValue($name)
    {
        return defined('self::' . $name) ? constant('self::' . $name) : null;
    }

    public static function getValues()
    {
        return [
            self::success,
            self::failed,
            self::unauthorized,
        ];
    }
}
