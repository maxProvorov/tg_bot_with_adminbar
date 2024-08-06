<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';

    public function toString(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::BLOCKED => 'Blocked',
        };
    }
}