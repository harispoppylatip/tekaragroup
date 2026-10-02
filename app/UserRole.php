<?php

namespace App;

enum UserRole: string
{
    case Admin = 'admin';
    case Member = 'member';

    /**
     * Label shown in the panel.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Member => 'Anggota',
        };
    }
}
