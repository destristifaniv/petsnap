<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AkunResource\Pages;
use App\Models\Akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\SelectFilter;

class AkunResource extends Resource
{
    protected static ?string $model = Akun::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('akuns')
                    ->required(),
            Forms\Components\TextInput::make('nama')->required(),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn (string $context) => $context === 'create')
                ->label('Password')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state)),

            Forms\Components\Select::make('role')
                ->options([
                    'pemilik' => 'Pemilik Hewan',
                    'dokter' => 'Dokter Hewan',
                ])
                ->required()
                ->label('Peran'),

            // Hanya tampil saat edit (jika record sudah ada)
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
            Tables\Columns\ImageColumn::make('foto')->label('Foto')->circular(),
            Tables\Columns\TextColumn::make('nama')->searchable(),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('role')->label('Peran'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->toggleable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diupdate')
                ->dateTime()
                ->toggleable(),
        ])
        ->filters([
            SelectFilter::make('role')
                ->label('Filter Peran')
                ->options([
                    'pemilik' => 'Pemilik Hewan',
                    'dokter' => 'Dokter Hewan',
                ]),
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
            'index' => Pages\ListAkuns::route('/'),
            'create' => Pages\CreateAkun::route('/create'),
            'edit' => Pages\EditAkun::route('/{record}/edit'),
        ];
    }
}
