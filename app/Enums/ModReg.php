<?php



namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ModReg: string implements HasLabel
{
    case A = 'CHEQUE';
    case B = 'VIREMENT';
    case C = 'MOBILE MONEY';
    case D = 'AUTRES';

     public function getLabel(): string
    {
        return match ($this) {
            self::A => 'CHEQUE',
            self::B => 'VIREMENT',
            self::C => 'MOBILE MONEY',
            self::D => 'AUTRES',
        };
    }
}
