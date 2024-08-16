<?php

namespace ErikGaal\BladeStreamlineIcons;

use BladeUI\Icons\Factory as BladeIconsFactory;
use ErikGaal\BladeStreamlineIcons\Commands\AccountCommand;
use ErikGaal\BladeStreamlineIcons\Commands\GetCommand;
use ErikGaal\BladeStreamlineIcons\Commands\LoginCommand;
use ErikGaal\BladeStreamlineIcons\Commands\SaveCommand;
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

        $this->callAfterResolving(BladeStreamlineIcons::class, function (BladeStreamlineIcons $streamline) {
            foreach (config('blade-streamline-icons.family_aliases') as $alias => $family) {
                $streamline->addFamilyAlias($alias, $family);
            }
        });

        $this->app->bind(StreamlineCredentials::class, function () {
            return StreamlineCredentials::loadFromFile();
        });
    }
}
