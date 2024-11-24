<?php

namespace ElmudoDev\FilamentSelectTable;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

    public Model | Closure | null $relationship = null;

    public mixed $existingRecords;

    public ?string $componentId = null;

    public array | int | null $selectedRecords = null;

    public bool $isMultiple = false;

    /**
     * @var array<\Filament\Tables\Columns\> | Closure | null
     */
    public array | Closure | null $schema;

    public function table(Table $table): Table
    {
        $items = [];
        if (is_array($this->schema)) {
            foreach ($this->schema as $value) {
                $items[] = $value;
            }
            $this->schema = [];
        }

        return $table->columns($items);
    }

    public function makeTable(): Table
    {
        return $this
            ->makeBaseTable()
            ->query($this->relationship->newQuery())
            ->headerActions([
                BulkAction::make('filament-select-add-relationship')
                    ->label($this->labelRelationshipAdd)
                    ->action(function (FilamentSelectTable $livewire, $records, Collection $selectedRecords) {
                        $livewire->dispatch('filament-select-table', record_ids: $records->pluck('id'));
                        $livewire->dispatch('close-modal', id: $this->componentId . '-form-component-action');
                    })->visible($this->isMultiple),
            ])
            ->actions([
                Action::make('add')
                    ->label($this->labelRelationshipAdd)
                    ->action(function (FilamentSelectTable $livewire, $record) {
                        $livewire->dispatch('filament-select-table', record_ids: $record->id);

                        $livewire->dispatch('close-modal', id: $this->componentId . '-form-component-action');
                    })
                    ->disabled(fn (Model $record) => $record->id == $this->selectedRecords)
                    ->visible(! $this->isMultiple),
            ]);
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
