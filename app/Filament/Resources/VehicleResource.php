<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),

                        Forms\Components\Select::make('is_popular')->options([true => 'Popular', false => 'Not Popular'])->required(),

                        Forms\Components\FileUpload::make('thumbnail')->image()->required(),

                        Forms\Components\Repeater::make('photos')->relationship('photos')->schema([
                            Forms\Components\FileUpload::make('photo')->required(),
                        ]),
                    ]),


                Fieldset::make('Additional')
                    ->schema([
                        Forms\Components\TextInput::make('horse_power')->required()->numeric()->prefix('HP'),
                        Forms\Components\TextInput::make('max_speed')->required()->numeric()->prefix('KMH'),
                        Forms\Components\TextInput::make('cc')->required()->numeric()->prefix('Power CC'),

                        Forms\Components\Textarea::make('about')->required(),

                        Forms\Components\TextInput::make('duration')->required()->numeric()->prefix('Days'),
                        Forms\Components\TextInput::make('price')->required()->numeric()->prefix('IDR'),

                        Forms\Components\Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Popular'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('category')
                    ->relationship('category', 'name'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
