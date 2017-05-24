<?php
namespace App\Exceptions;
class InvalidArgumentException extends \InvalidArgumentException
{
    const CODE_TOKEN_NOT_PRESENT= 450;
    const CODE_TIMESTAMP_NOT_PRESENT= 451;
    const CODE_INVALID_TOKEN= 452;

    public static function invalidToken(){
        return new self('Invalid acesss token',self::CODE_INVALID_TOKEN);
    }

    public static function timestampNotPresent(){
        return new self('Timestamp was not found in GET parameters',self::CODE_TIMESTAMP_NOT_PRESENT);
    }

    public static function tokenNotPresent(){
        return new self('Token was not found in GET parameters',self::CODE_TOKEN_NOT_PRESENT);
    }
}