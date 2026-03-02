<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\OrderResource;
use App\Services\OrderStatusService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use InvalidArgumentException;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('admin.order.print', $this->getRecord()))
                ->openUrlInNewTab(),
            Actions\Action::make('updateStatus')
                ->label('Update status')
                ->icon('heroicon-o-arrow-path')
                ->form(fn (Form $form) => $form->schema([
                    Forms\Components\Select::make('status')
                        ->label('New status')
                        ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $s) => [$s->value => $s->label()]))
                        ->required()
                        ->native(false),
                ]))
                ->action(function (array $data): void {
                    $order = $this->getRecord();
                    $target = OrderStatus::from($data['status']);

                    try {
                        app(OrderStatusService::class)->updateStatus(
                            $order,
                            $target,
                            auth()->user()
                        );
                    } catch (InvalidArgumentException $e) {
                        Notification::make()
                            ->danger()
                            ->title('Invalid status transition')
                            ->body($e->getMessage())
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Status updated')
                        ->send();
                }),
        ];
    }
}
