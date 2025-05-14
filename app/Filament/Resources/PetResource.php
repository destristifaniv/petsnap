<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use App\Models\Akun;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('pets')
                    ->required(),
                Forms\Components\TextInput::make('nama')->required(),
                Forms\Components\TextInput::make('jenis')->required(),
                Forms\Components\TextInput::make('warna')->label('Warna Hewan')->required(),
                Forms\Components\TextInput::make('usia')->required()->numeric(),
                Forms\Components\TextInput::make('kondisi')->required()
                    ->label('Kondisi'),
                Forms\Components\Select::make('pemilik_id')
                    ->options(Akun::all()->pluck('nama', 'id'))
                    ->required()
                    ->label('Pemilik'),

                // Informasi waktu hanya ditampilkan di form edit, bukan di create
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
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('jenis'),
                Tables\Columns\TextColumn::make('warna')->label('Warna Hewan'),
                Tables\Columns\TextColumn::make('usia'),
                // Tampilkan kondisi sebagai badge + warna
                Tables\Columns\TextColumn::make('kondisi')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'sehat' => 'success',
                        'sakit' => 'danger',
                        'perlu perawatan' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pemilik.nama')->label('Pemilik'),

                // Toggleable column untuk waktu
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
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }

    public function pemilik()
    {
        return $this->belongsTo(Akun::class, 'pemilik_id');
    }
}
