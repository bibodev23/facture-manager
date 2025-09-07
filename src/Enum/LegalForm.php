<?php

namespace App\Enum;

enum LegalForm: string 
{
    case MICRO_ENTREPRENEUR = 'Micro-entrepreneur'; 
    case EI = 'EI'; 
    case EIRL = 'EIRL'; 
    case EURL = 'EURL'; 
    case SARL = 'SARL'; 
    case SASU = 'SASU'; 
    case SAS = 'SAS';
    
    public function getLabel(): string{
        return match ($this) {
            self::MICRO_ENTREPRENEUR => 'Micro-entrepreneur',
            self::EI => 'Enterprise individuelle',
            self::EIRL => 'EIRL',
            self::EURL => 'EURL',
            self::SARL => 'SARL',
            self::SASU => 'SASU',
            self::SAS => 'SAS',
        };
    }
}