<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', 'فطرة'),
            'site_description' => Setting::get('site_description', 'منصة تعليمية متكاملة'),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'consultation_intro' => Setting::get('consultation_intro', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('site_name')->label('اسم الموقع')->required(),
                Textarea::make('site_description')->label('وصف الموقع'),
                TextInput::make('contact_email')->label('البريد الإلكتروني للتواصل'),
                TextInput::make('contact_phone')->label('رقم الهاتف'),
                Textarea::make('consultation_intro')->label('نص تعريف الاستشارات'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()->title('تم حفظ الإعدادات')->success()->send();
    }
}
