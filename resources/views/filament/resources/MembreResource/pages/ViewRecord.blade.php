<x-filament::page>
    {!! QrCode::size(250)
    //->eyeColor(0, 255, 255, 255, 0, 0, 0)
    ->generate($record->matmem) !!}
</x-filament::page>