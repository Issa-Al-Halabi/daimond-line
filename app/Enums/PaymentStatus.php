<?php

namespace App\Enums;

class PaymentStatus
{
    const pending = "P";
    const accepted = "A";
    const failed = "F";
    const canceled = "C";


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
            self::pending,
            self::accepted,
            self::failed,
            self::canceled,
        ];
    }
}
