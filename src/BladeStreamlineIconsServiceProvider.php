<?php

namespace ErikGaal\BladeStreamlineIcons;

use BladeUI\Icons\Factory;
use BladeUI\Icons\Factory as BladeIconsFactory;
use ErikGaal\BladeStreamlineIcons\Commands\AccountCommand;
use ErikGaal\BladeStreamlineIcons\Commands\GetCommand;
use ErikGaal\BladeStreamlineIcons\Commands\LoginCommand;
use ErikGaal\BladeStreamlineIcons\Commands\SaveCommand;
use ErikGaal\BladeStreamlineIcons\Solutions\DownloadIconSolution;
use ErikGaal\BladeStreamlineIcons\Solutions\SolutionProviders\StreamlineBladeIconSolutionProvider;
use Spatie\Ignition\Contracts\SolutionProviderRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BladeStreamlineIconsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('blade-streamline-icons')
            ->hasConfigFile()
            ->hasCommands(
                LoginCommand::class,
                AccountCommand::class,
                GetCommand::class,
                SaveCommand::class,
            );
    }

    public function packageRegistered()
    {
        $this->callAfterResolving(BladeIconsFactory::class, function (BladeIconsFactory $factory) {
            $config = $this->app->make('config')->get('blade-streamline-icons');

            $factory->add('streamline', [
                'path' => resource_path('icons/streamline'),
                ...$config,
            ]);
        });

        $this->app->singleton(BladeStreamlineIcons::class, function () {
            $streamline = new BladeStreamlineIcons($this->app->make(StreamlineApi::class));

            foreach (config('blade-streamline-icons.family_aliases') as $alias => $family) {
                $streamline->addFamilyAlias($alias, $family);
            }

            return $streamline;
        });

        $this->app->bind(StreamlineCredentials::class, function () {
            return StreamlineCredentials::loadFromFile();
        });
    }

    public function packageBooted()
    {
        if ($this->app->bound(SolutionProviderRepository::class)) {
            $this->app->make(SolutionProviderRepository::class)->registerSolutionProviders([
                StreamlineBladeIconSolutionProvider::class,
            ]);
        }
    }
}
