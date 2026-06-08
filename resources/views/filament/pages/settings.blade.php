@php
    use function Filament\Support\generate_icon_html;
    use function Filament\Support\get_color_name;
@endphp

<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::button wire:click="save" type="submit" class="mt-4">
        حفظ الإعدادات
    </x-filament::button>
</x-filament-panels::page>
