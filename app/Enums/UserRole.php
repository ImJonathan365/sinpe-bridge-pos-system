<?php

namespace App\Enums;

enum UserRole: string
{
    case CASHIER = 'cashier';
    case ADMINISTRATOR = 'administrator';

    public function label(): string
    {
        return match ($this) {
            self::CASHIER => 'Cashier',
            self::ADMINISTRATOR => 'Administrator',
        };
    }
}