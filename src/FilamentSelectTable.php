<?php

namespace ElmudoDev\FilamentSelectTable;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
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

    public string $labelRelationshipAdd;

    public string $titleRelationshipTable;

    public string | Closure | null $relationship = null;

    public mixed $existingRecords;

    public ?string $componentId = null;


    protected function getTableQuery()
    {
        return $this->ownerRecord->{$this->relationship}()->getQuery();
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
        return $this
            ->makeBaseTable();
    }

    protected function getTableHeaderActions(): array
    {

        return [
            BulkAction::make('filament-select-add-relationship')
                ->label($this->labelRelationshipAdd)
                ->action(function (Component $livewire, $records) {
                    $livewire->dispatch('filament-select-table', record_ids: $records->pluck('id'));

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
