<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Kelola User';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Kelola User';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    /**
     * Hanya superadmin yang bisa melihat menu ini.
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi User')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->maxLength(255)
                            ->helperText(fn (string $operation): string =>
                                $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password.' : ''
                            ),

                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'superadmin' => 'Super Admin',
                                'admin' => 'Admin (Guru)',
                            ])
                            ->required()
                            ->default('admin')
                            ->reactive(),

                        Forms\Components\Select::make('school_profile_id')
                            ->label('Sekolah Tempat Mengajar')
                            ->relationship('schoolProfile', 'nama_sekolah')
                            ->searchable()
                            ->preload()
                            ->required(fn (Forms\Get $get) => $get('role') === 'admin')
                            ->hidden(fn (Forms\Get $get) => $get('role') === 'superadmin'),

                        Forms\Components\Select::make('kelas')
                            ->label('Kelas yang Diajar')
                            ->options([
                                '1A' => '1A', '1B' => '1B',
                                '2A' => '2A', '2B' => '2B',
                                '3A' => '3A', '3B' => '3B',
                                '4A' => '4A', '4B' => '4B',
                                '5A' => '5A', '5B' => '5B',
                                '6A' => '6A', '6B' => '6B',
                            ])
                            ->required(fn (Forms\Get $get) => $get('role') === 'admin')
                            ->hidden(fn (Forms\Get $get) => $get('role') === 'superadmin'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'superadmin' => 'danger',
                        'admin' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin (Guru)',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('schoolProfile.nama_sekolah')
                    ->label('Sekolah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin (Guru)',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    // Jangan bisa hapus diri sendiri
                    ->hidden(fn ($record) => $record->id === auth()->id()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
