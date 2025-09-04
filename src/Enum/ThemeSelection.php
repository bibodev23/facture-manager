<?php

namespace App\Enum;
/**
 * Enum class representing the status of theme selection for design.
 */

enum ThemeSelection: string
{
    case DefaultTheme = 'default-theme';
    case AlternativeTheme = 'alternative-theme';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::DefaultTheme => 'Thème par défaut',
            self::AlternativeTheme => 'Thème alternatif',
        };
    }
}