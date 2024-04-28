<?php

namespace App\Enums;

class BookingStatus
{
    const pending = "pending";
    const accepted = "accepted";
    const started = "started";
    const arrived = "arrived";
    const canceld = "canceld";
    const ended = "ended";
    const wait_for_payment = "wait for payment";


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
            self::started,
            self::arrived,
            self::canceld,
            self::ended,
            self::wait_for_payment,
        ];
    }
}