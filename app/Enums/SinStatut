<?php



namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SinStatut: string implements HasLabel
{
    case A = 'A VERIFIER';
    case B = 'VERIFIE';
    case C = 'REGLES';

     public function getLabel(): string
    {
        return match ($this) {
            self::A => 'A VERIFIER',
            self::B => 'VERIFIE',
            self::C => 'REGLES',
        };
    }
}
