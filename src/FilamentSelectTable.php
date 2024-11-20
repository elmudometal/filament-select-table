<?php

namespace ElmudoDev\FilamentSelectTable;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class FilamentSelectTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable {
        makeTable as makeBaseTable;
    }

    public string $statePath;

    public Model $ownerRecord;

    public mixed $existingRecords;

    public ?string $componentId = null;

    protected function getTableQuery()
    {
        // Define aquí la consulta para la tabla, por ejemplo, todos los usuarios
        return \App\Models\User::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nombre')->sortable(),
            TextColumn::make('email')->label('Correo')->sortable(),
            // Agrega más columnas según tus necesidades
        ];
    }

    public function makeTable(): Table
    {
        return $this->makeBaseTable()
            ->query(fn ($query) => \App\Models\User::query());
    }

    public function checkIfRecordIsSelectableUsing()
    {
        return false;
    }

    protected function getTableHeaderActions(): array
    {
        return [
            BulkAction::make('delete')
                ->label('agregar')
                ->action(function (Component $livewire, $records) {
                    $livewire->dispatch('select-table-aaaaaaa', record_ids: $records->pluck('id'));

                    $livewire
                        ->dispatch('close-modal', id: $this->componentId . '-form-component-action');
                }),
        ];
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}
