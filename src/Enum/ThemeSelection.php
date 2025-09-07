<?php

namespace App\Enum;
/**
 * Enum class representing the status of theme selection for design.
 */

enum ThemeSelection: string
{
    case DefaultTheme = 'default';
    case AlternativeTheme = 'alternative';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::DefaultTheme => 'Sans logo',
            self::AlternativeTheme => 'Avec logo',
        };
    }
}