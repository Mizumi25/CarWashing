<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
 

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'fas-hand-sparkles';
    
    protected static ?string $navigationLabel = 'Service';
    
    protected static ?string $modelLabel = 'Manage Services';
    
    protected static ?string $navigationGroup = 'Manage';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Section::make('Main Detail')->description('Put Details here for Service')->schema([
                  Forms\Components\TextInput::make('service_name')->required()->maxLength(255)
                    ->rule('regex:/^[a-zA-Z0-9\s]+$/')
                    ->label('Service Name'),
                  Forms\Components\FileUpload::make('icon')->disk('public') ->directory('service_icons')->required(),
                  Forms\Components\MarkdownEditor::make('description')->required()->columnSpanFull(),
                ])->columns(2),
                Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Bill')
                        ->schema([
                            Forms\Components\TextInput::make('price')->numeric()->prefix('P')->required(),
                        ]),
                    Tabs\Tab::make('Activity')
                        ->schema([
                            Forms\Components\TextInput::make('duration')->numeric()->suffix('mins.')->maxLength(255)->required(),
                            Toggle::make('is_active')
                            ->onColor('info')
                             ->offColor('gray')
                            ->inline(),
                            Forms\Components\TextInput::make('category')->required()->maxLength(255),
                        ])->columns(3),
                ])
                ->activeTab(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon'),
                Tables\Columns\TextColumn::make('service_name')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(10),
                Tables\Columns\TextColumn::make('price')->prefix('P')->searchable(),
                Tables\Columns\TextColumn::make('duration')->suffix('mins.')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->trueColor('info')->falseColor('warning'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('popularity'),
                Tables\Columns\TextColumn::make('totalRevenue')
                ->label('Total Revenue')
                ->prefix('P'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}