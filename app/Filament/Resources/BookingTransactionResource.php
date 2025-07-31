<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Wizard::make([

                    Forms\Components\Wizard\Step::make('Product and Price')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('vehicle_id')
                                        ->relationship('vehicle', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {

                                            $vehicle = Vehicle::find($state);
                                            $price = $vehicle ? $vehicle->price : 0;
                                            $duration = $vehicle ? $vehicle->duration : 0;
                                            $insurance = 500000;

                                            $tax = 0.11;
                                            $totalMaxAmount = $tax * $price;

                                            $totalAmount = $price + $totalMaxAmount + $insurance;

                                            $set('total_max_amount', number_format($totalMaxAmount, 0, '', ''));
                                            $set('insurance', $insurance);
                                            $set('price', $price);
                                            $set('duration', $duration);
                                            $set('total_amount', number_format($totalAmount, 0, '', ''));
                                        })

                                        ->afterStateHydrated(function ($state, callable $set, callable $get) {

                                            $vehicle = Vehicle::find($state);
                                            $price = $vehicle ? $vehicle->price : 0;
                                            $duration = $vehicle ? $vehicle->duration : 0;
                                            $insurance = 500000;

                                            $tax = 0.11;
                                            $totalMaxAmount = $tax * $price;

                                            $totalAmount = $price + $totalMaxAmount + $insurance;

                                            $set('total_max_amount', number_format($totalMaxAmount, 0, '', ''));
                                            $set('insurance', $insurance);
                                            $set('price', $price);
                                            $set('duration', $duration);
                                            $set('total_amount', number_format($totalAmount, 0, '', ''));
                                        }),

                                    Forms\Components\TextInput::make('duration')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('Days'),

                                    Forms\Components\TextInput::make('total_amount')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),

                                    Forms\Components\TextInput::make('price')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),

                                    Forms\Components\TextInput::make('total_max_amount')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),

                                    Forms\Components\TextInput::make('insurance')
                                        ->required()
                                        ->numeric()
                                        ->readOnly()
                                        ->prefix('IDR'),

                                    Forms\Components\DatePicker::make('started_at')
                                        ->required(),

                                    Forms\Components\Select::make('alpina_store_id')
                                        ->relationship('alpinaStore', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                ]),
                        ]),

                    Forms\Components\Wizard\Step::make('Customer Information')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                                Forms\Components\TextInput::make('phone')->required()->maxLength(255),
                                Forms\Components\TextInput::make('email')->required()->maxLength(255),
                            ]),
                        ]),

                    Forms\Components\Wizard\Step::make('Payment Information')
                        ->schema([
                            Forms\Components\TextInput::make('booking_trx_id')->required()->maxLength(255),

                            ToggleButtons::make('is_paid')
                                ->label('Apakah sudah membayar?')
                                ->boolean()
                                ->grouped()
                                ->icons([
                                    true => "heroicon-o-pencil",
                                    false => "heroicon-o-clock",
                                ])
                                ->required(),

                            Forms\Components\FileUpload::make('proof')->image()->required(),
                        ]),
                ])

                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('vehicle.thumbnail'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('booking_trx_id')->searchable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Terverifikasi'),
            ])
            ->filters([
                SelectFilter::make('vehicle_id')
                    ->label('vehicle')
                    ->relationship('vehicle', 'name'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('Approve')
                    ->label('Approve')
                    ->action(function (BookingTransaction $record) {
                        $record->is_paid = true;
                        $record->save();

                        Notification::make()->title('Transaction Approve')->success()->body('The Transaction has been successfully approved.')->send();
                    })->color('success')->requiresConfirmation()->visible(fn(BookingTransaction $record) => !$record->is_paid),

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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
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
