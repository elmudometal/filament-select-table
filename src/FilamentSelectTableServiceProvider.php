<?php

namespace ElmudoDev\FilamentSelectTable;

use ElmudoDev\FilamentSelectTable\Commands\FilamentSelectTableCommand;
use ElmudoDev\FilamentSelectTable\Testing\TestsFilamentSelectTable;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentSelectTableServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-select-table';

    public static string $viewNamespace = 'filament-select-table';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToStarRepoOnGitHub('elmudo-dev/filament-select-table');
            });

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        $this->registerLivewireComponents();

        FilamentAsset::registerScriptData(
            $this->getScriptData()
        );

        // Testing
        Testable::mixin(new TestsFilamentSelectTable);
    }

    private function registerLivewireComponents(): void
    {
        \Livewire\Livewire::component('elmudo-dev::filament-select-table', FilamentSelectTable::class);
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentSelectTableCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
