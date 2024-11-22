<?php

namespace ElmudoDev\FilamentSelectTable\Forms;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class FilamentSelectTable extends Select implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string | Htmlable | Closure | null $labelRelationshipAdd;

    protected string | Htmlable | Closure | null $titleRelationshipTable;

    protected array | Closure | null $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([])
            ->extraAlpineAttributes([
                'x-on:filament-select-table.window' => '($event) => $wire.dispatchFormEvent(\'filament-select-table\', \'$getStatePath()\', $event.detail.record_ids)',
            ]);

        $this->afterStateHydrated(static function (FilamentSelectTable $component, $state) {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });

        $this->registerListeners([
            'filament-select-table' => [
                function (FilamentSelectTable $component, $statePath, array | int $records) {
                    $component->state($records);
                    $component->callAfterStateUpdated();
                }],
        ]);

        $this->mutateDehydratedStateUsing(static function (?array $state): array {
            return array_values($state ?? []);
        });

    }

    public function getCreateOptionAction(): ?Action
    {
        if ($this->isDisabled()) {
            return null;
        }

        $action = Action::make($this->getCreateOptionActionName())
            ->modalContent(
                function (FilamentSelectTable $component) {
                    $componentName = \ElmudoDev\FilamentSelectTable\FilamentSelectTable::class;

                    return new HtmlString(
                        Blade::render(
                            string: "@livewire('{$componentName}',  ['statePath' => \$statePath, 'ownerRecord' => \$ownerRecord, 'labelRelationshipAdd'=> \$labelRelationshipAdd, 'titleRelationshipTable'=> \$titleRelationshipTable, 'relationship' => \$relationship, 'schema' => \$schema, 'isMultiple'=>\$isMultiple, 'selectedRecords' => \$selectedRecords, 'componentId' => \$componentId])",
                            data: [
                                'statePath' => $component->getStatePath(),
                                'ownerRecord' => $component->getRecord(),
                                'labelRelationshipAdd' => $component->getLabelRelationshipAdd(),
                                'titleRelationshipTable' => $component->titleRelationshipTable,
                                'relationship' => $component->getRelationship() ? $component->getRelationship()->getModel() : null,
                                'schema' => $this->evaluate($component->schema),
                                'isMultiple' => $component->isMultiple,
                                'selectedRecords' => $component->getState(),
                                'componentId' => $component->getLivewire()->getId(),
                            ]
                        )
                    );
                }
            )
            ->modalSubmitAction(fn () => false)
            ->modalCancelAction(fn () => false)
            ->color('gray')
            ->icon('heroicon-m-plus')
            ->iconButton()
            ->modalHeading($this->titleRelationshipTable);

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

    public function labelRelationshipAdd(string | Htmlable | Closure | null $labelRelationshipAdd): static
    {
        $this->labelRelationshipAdd = $labelRelationshipAdd;

        return $this;
    }

    public function titleRelationshipTable(string | Htmlable | Closure | null $titleRelationshipTable): static
    {
        $this->titleRelationshipTable = $titleRelationshipTable;

        return $this;
    }

    /**
     * @param  array<Component> | Closure | null  $schema
     */
    public function schema(array | Closure | null $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    public function getLabelRelationshipAdd(): string | Htmlable | null
    {
        if ($this->labelRelationshipAdd === null && $this->hasRelationship()) {
            $labelRelationshipAdd = (string) str($this->getRelationshipName())
                ->before('.')
                ->kebab()
                ->replace(['-', '_'], ' ')
                ->ucfirst();

            return $labelRelationshipAdd;
        }

        $labelRelationshipAdd = (string) str($this->labelRelationshipAdd)
            ->afterLast('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();

        return $labelRelationshipAdd;
    }
}
