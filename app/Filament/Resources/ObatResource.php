<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatResource\Pages;
use App\Models\Obat;
use App\Models\Diagnosa;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('diagnosa_id')
                ->label('Diagnosa')
                ->options(Diagnosa::with('hewan')->get()->mapWithKeys(function ($diagnosa) {
                    return [$diagnosa->id => $diagnosa->hewan->nama . ' - ' . $diagnosa->tanggal_diagnosa];
                }))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('nama_obat')->required(),
            Forms\Components\TextInput::make('dosis')->required(),
            Forms\Components\Textarea::make('catatan'),

            // Hanya ditampilkan saat edit
            Forms\Components\Placeholder::make('created_at')
                ->label('Dibuat pada')
                ->content(fn ($record) => $record->created_at?->format('d M Y H:i'))
                ->visible(fn ($record) => $record !== null),

            Forms\Components\Placeholder::make('updated_at')
                ->label('Diupdate pada')
                ->content(fn ($record) => $record->updated_at?->format('d M Y H:i'))
                ->visible(fn ($record) => $record !== null),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('diagnosa.hewan.nama')->label('Hewan'),
            Tables\Columns\TextColumn::make('nama_obat'),
            Tables\Columns\TextColumn::make('dosis'),
            Tables\Columns\TextColumn::make('catatan')->limit(30)->tooltip(fn ($record) => $record->catatan),

            // Kolom toggleable waktu
            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->toggleable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diupdate')
                ->dateTime()
                ->toggleable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->color('change'),
            Tables\Actions\DeleteAction::make()->color('danger'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()->color('danger'),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListObats::route('/'),
            'create' => Pages\CreateObat::route('/create'),
            'edit' => Pages\EditObat::route('/{record}/edit'),
        ];
    }
}
