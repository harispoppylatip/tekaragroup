<?php

namespace App;

enum ProjectCategory: string
{
    case Website = 'website';
    case Iot = 'iot';
    case Lainnya = 'lainnya';

    /**
     * Label shown to visitors.
     */
    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Iot => 'IoT',
            self::Lainnya => 'Lainnya',
        };
    }
}
