<?php

namespace ElmudoDev\FilamentSelectTable;

use Closure;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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

    public mixed $schema;

    public function table(Table $table): Table
    {
        return $table->columns($this->schema::table());
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
                    ->disabled(fn (Model $record) => $record->getKey() == $this->selectedRecords)
                    ->visible(! $this->isMultiple),
            ]);
    }

    protected function getTableQuery(): Builder | Relation | null
    {
        return $this->relationship->newQuery();
    }

    public function mountSelectTableBulkAction(string $name, ?array $selectedRecords = null): mixed
    {
        $this->mountedTableBulkAction = $name;

        if ($selectedRecords !== null) {
            $this->selectedTableRecords = $selectedRecords;
        }

        $action = $this->getMountedTableBulkAction();

        if (! $action) {
            return null;
        }

        if ($action->isDisabled()) {
            return null;
        }

        $this->cacheMountedTableBulkActionForm(mountedBulkAction: $action);

        return null;
    }

    public function render()
    {
        return <<<'HTML'
        <div>

            {{ $this->table }}

            @script
                <script>
                    $wire.mountSelectTableBulkAction('filament-select-add-relationship', @js($this->selectedRecords)) /* The IDs of your selected rows */
                </script>
            @endscript

            @script
            <script>

                document.addEventListener('trigger-select-records', function(event) {

                    event.detail[0].records.forEach(record => {

                        const checkbox = document.querySelector('tr[wire\\:key="' + event.detail[0].livewireId + '.table.records.' + record + '"] input[type="checkbox"]')

                        if (checkbox && !checkbox.checked) {
                            checkbox.click();
                        }
                    });

                });

            </script>

            @endscript

        </div>

        HTML;

    }

    public function rendered()
    {
        $this->dispatch('trigger-select-records', [
            'records' => $this->selectedRecords, // The IDs of your selected rows
            'livewireId' => $this->id(), // Required to link to this specific component incase you need to have multiple of the same component or multiple components supporting preselected rows
        ]);
    }
}
