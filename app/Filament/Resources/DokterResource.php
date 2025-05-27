<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokterResource\Pages;
use App\Models\Dokter;
use App\Models\Akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class DokterResource extends Resource
{
    protected static ?string $model = Dokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('akun_id')
                ->label('Akun')
                ->options(Akun::all()->pluck('email', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\FileUpload::make('foto')
                ->image()
                ->directory('dokters')
                ->label('Foto Profil')
                ->imageEditor()
                ->maxSize(2048)
                ->columnSpan('full'),

            Forms\Components\TextInput::make('nama')
                ->label('Nama Dokter')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('alamat')
                ->label('Alamat')
                ->maxLength(255),

            Forms\Components\TextInput::make('no_hp')
                ->label('No HP')
                ->tel()
                ->maxLength(20),

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
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('nama')->label('Nama Dokter')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('akun.email')->label('Email Akun')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('no_hp')->label('No HP')->toggleable(),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diupdate')->dateTime()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('akun_id')
                    ->label('Filter berdasarkan Akun')
                    ->options(Akun::all()->pluck('email', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokters::route('/'),
            'create' => Pages\CreateDokter::route('/create'),
            'edit' => Pages\EditDokter::route('/{record}/edit'),
        ];
    }
}
