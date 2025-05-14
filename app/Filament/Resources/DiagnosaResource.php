<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiagnosaResource\Pages;
use App\Models\Diagnosa;
use App\Models\Pet;
use App\Models\Akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DiagnosaResource extends Resource
{
    protected static ?string $model = Diagnosa::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('hewan_id')
                ->label('Hewan')
                ->options(Pet::all()->pluck('nama', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('dokter_id')
                ->label('Dokter')
                ->options(Akun::where('role', 'dokter')->pluck('nama', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('tanggal_diagnosa')
                ->label('Tanggal Diagnosa')
                ->required(),

            Forms\Components\Textarea::make('catatan')
                ->label('Catatan Diagnosa')
                ->required(),

            // Tampil hanya saat edit
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
            Tables\Columns\TextColumn::make('hewan.nama')
                ->label('Hewan')
                ->searchable(),  // Search berdasarkan nama hewan saja

            Tables\Columns\TextColumn::make('dokter.nama')
                ->label('Dokter')
                ->searchable(),

            Tables\Columns\TextColumn::make('tanggal_diagnosa')
                ->label('Tanggal')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('catatan')
                ->limit(30)
                ->tooltip(fn ($record) => $record->catatan)
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->toggleable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diupdate')
                ->dateTime()
                ->toggleable(),
        ])
        ->filters([  // Tidak ada filter karena pencarian berdasarkan nama hewan sudah cukup
            // Filter opsional berdasarkan nama hewan (tidak diperlukan jika hanya mencari dengan searchable)
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
            'index' => Pages\ListDiagnosas::route('/'),
            'create' => Pages\CreateDiagnosa::route('/create'),
            'edit' => Pages\EditDiagnosa::route('/{record}/edit'),
        ];
    }
}
