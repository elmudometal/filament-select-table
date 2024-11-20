<?php

namespace ElmudoDev\FilamentSelectTable\Forms;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class FilamentSelectTable extends Select implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerListeners([
            'select-table-aaaaaaa2' => [
                function (FilamentSelectTable $component, $statePath, array $records) {
                    $component->state($records);
                    Log::warning('sdsdsds2');
                }],
        ]);

    }

    protected function getTableQuery()
    {
        // Define aquí la consulta para la tabla, por ejemplo, todos los usuarios
        return \App\Models\User::query();
    }

    protected function getTableColumns(): array
    {
        return [
            CheckboxColumn::make('is_admin'),
            TextColumn::make('name')->label('Nombre')->sortable(),
            TextColumn::make('email')->label('Correo')->sortable(),
            // Agrega más columnas según tus necesidades
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('estado')
                ->label('Estado')
                ->options([
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                ]),
            // Añade otros filtros necesarios
        ];
    }

    public function getCreateOptionAction(): ?Action
    {
        if ($this->isDisabled()) {
            return null;
        }

        $action = Action::make($this->getCreateOptionActionName())
            ->modalContent(
                function (FilamentSelectTable $component) {
                    $componentName = 'user-table';

                    return new HtmlString(
                        Blade::render(
                            string: "@livewire('{$componentName}',  ['statePath' => \$statePath, 'ownerRecord' => \$ownerRecord, 'existingRecords' => \$existingRecords, 'componentId' => \$componentId])",
                            data: [
                                'statePath' => $component->getStatePath(),
                                'ownerRecord' => $component->getRecord(),
                                'existingRecords' => collect($component->getState())->pluck('id')->toArray(),
                                'componentId' => $component->getLivewire()->getId(),
                            ]
                        )
                    );
                }
            )
            //->modalSubmitAction(fn(array $data) => dd($data))
            ->action(function (array $data) {
                dd($data, 'aaa');
            })
            ->modalCancelAction(fn () => false)
            ->color('gray')
            ->icon(FilamentIcon::resolve('forms::components.select.actions.create-option') ?? 'heroicon-m-plus')
            ->iconButton()
            ->modalHeading($this->getCreateOptionModalHeading() ?? __('filament-forms::components.select.actions.create_option.modal.heading'));

        if ($this->modifyManageOptionActionsUsing) {
            $action = $this->evaluate($this->modifyManageOptionActionsUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        if ($this->modifyCreateOptionActionUsing) {
            $action = $this->evaluate($this->modifyCreateOptionActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }
}
