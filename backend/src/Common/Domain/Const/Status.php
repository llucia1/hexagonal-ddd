<?php
declare(strict_types=1);
namespace Fynkus\Common\Domain\Const;

class Status {
    public const FREE = 'free';
    public const RESERVED = 'reserved';


    
    public static function isValid(?string $status): bool
    {
        return in_array($status, [self::FREE, self::RESERVED], true);
    }
}