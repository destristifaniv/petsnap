<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use App\Models\Akun; // Tetap import jika Anda menggunakan Akun di tempat lain
use App\Models\Pemilik; // <-- PENTING: Import model Pemilik
use App\Models\Dokter; // <-- PENTING: Import model Dokter jika perlu menampilkan di tabel

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select; // Import Select component
use Filament\Forms\Components\TextInput; // Import TextInput component
use Filament\Forms\Components\FileUpload; // Import FileUpload component
use Filament\Forms\Components\Placeholder; // Import Placeholder component
use Filament\Tables\Columns\ImageColumn; // Import ImageColumn
use Filament\Tables\Columns\TextColumn; // Import TextColumn


class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('foto') // Nama kolom di DB adalah 'foto'
                    ->image()
                    ->directory('pets') // Folder di storage/app/public
                    ->nullable() // <-- PERBAIKAN: `foto` adalah NULLABLE di DB
                    ->label('Foto Hewan'), // Label yang lebih user-friendly

                TextInput::make('nama')
                    ->required()
                    ->maxLength(255), // Sesuai varchar(255)

                TextInput::make('jenis')
                    ->required()
                    ->maxLength(255),

                TextInput::make('warna')
                    ->label('Warna Hewan')
                    ->nullable() // <-- PERBAIKAN: `warna` adalah NULLABLE di DB
                    ->maxLength(255),

                TextInput::make('usia')
                    ->required()
                    ->numeric()
                    ->minValue(0) // Usia tidak boleh negatif
                    ->maxValue(100), // Batasan usia wajar

                TextInput::make('kondisi')
                    ->required() // <-- Sesuai `NOT NULL` di DB
                    ->maxLength(255)
                    ->label('Kondisi'),

                // PENTING: Select pemilik_id dari model Pemilik, bukan Akun
                // Karena pets.pemilik_id menunjuk ke pemiliks.id
                Select::make('pemilik_id')
                    ->label('Pemilik')
                    ->options(Pemilik::all()->pluck('nama', 'id')) // <-- PERBAIKAN: Ambil dari model Pemilik
                    ->required()
                    ->searchable() // Mempermudah pencarian pemilik
                    ->preload(), // Memuat semua opsi di awal

                // Dokter ID (nullable)
                Select::make('dokter_id')
                    ->label('Dokter Penanggung Jawab')
                    ->options(
                        // Asumsi model Dokter memiliki kolom 'nama' dan 'id'
                        // Dan Dokter juga memiliki akun_id.
                        // Jika Dokter juga punya nama, gunakan Dokter::all()->pluck('nama', 'id')
                        // Atau, jika Anda ingin menampilkan email akun dokter:
                        Dokter::all()->mapWithKeys(function ($dokter) {
                            return [$dokter->id => $dokter->nama . ' (' . ($dokter->akun->email ?? 'N/A') . ')'];
                        })
                    )
                    ->nullable() // <-- `dokter_id` adalah NULLABLE di DB
                    ->searchable()
                    ->preload(),

                // Informasi waktu hanya ditampilkan di form edit (saat $record tidak null)
                Placeholder::make('created_at')
                    ->label('Dibuat pada')
                    ->content(fn ($record) => $record->created_at?->format('d M Y H:i'))
                    ->visible(fn ($record) => $record !== null && $record->created_at !== null),

                Placeholder::make('updated_at')
                    ->label('Diupdate pada')
                    ->content(fn ($record) => $record->updated_at?->format('d M Y H:i'))
                    ->visible(fn ($record) => $record !== null && $record->updated_at !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto') // Nama kolom di DB adalah 'foto'
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => url('/placeholder.png')), // Gambar default jika foto null

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(), // Tambahkan sortable

                TextColumn::make('jenis')
                    ->sortable(),

                TextColumn::make('warna')
                    ->label('Warna Hewan'),

                TextColumn::make('usia')
                    ->sortable(),

                TextColumn::make('kondisi')
                    ->label('Kondisi')
                    ->badge() // Tampilkan sebagai badge
                    ->color(fn ($state) => match ($state) { // Warna badge berdasarkan kondisi
                        'sehat' => 'success',
                        'sakit' => 'danger',
                        'perlu perawatan' => 'warning',
                        default => 'gray', // Kondisi lain
                    }),

                // Tampilkan nama pemilik (dari relasi 'pemilik')
                TextColumn::make('pemilik.nama') // Relasi Pemilik di model Pet
                    ->label('Pemilik')
                    ->searchable()
                    ->sortable(),

                // Tampilkan nama dokter (dari relasi 'dokter')
                TextColumn::make('dokter.nama') // Relasi Dokter di model Pet
                    ->label('Dokter PJ') // Penanggung Jawab
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara default, bisa ditoggle

                // Kolom waktu (opsional, bisa ditoggle)
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan jenis hewan
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'Kucing' => 'Kucing',
                        'Anjing' => 'Anjing',
                        'Kelinci' => 'Kelinci',
                        // ... tambahkan jenis lain yang relevan
                    ])
                    ->label('Filter Jenis'),

                // Filter berdasarkan pemilik
                Tables\Filters\SelectFilter::make('pemilik_id')
                    ->label('Filter Pemilik')
                    ->options(Pemilik::all()->pluck('nama', 'id'))
                    ->searchable(),

                // Filter berdasarkan kondisi
                Tables\Filters\SelectFilter::make('kondisi')
                    ->options([
                        'sehat' => 'Sehat',
                        'sakit' => 'Sakit',
                        'perlu perawatan' => 'Perlu Perawatan',
                    ])
                    ->label('Filter Kondisi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('primary'), // Ganti warna default ke primary
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

    // --- PENTING: Perbaiki relasi di model Pet.php ---
    // Relasi ini seharusnya ada di model Pet, bukan di Resource
    /*
    public function pemilik()
    {
        return $this->belongsTo(Akun::class, 'pemilik_id');
    }
    */
}