<?php

namespace ErikGaal\BladeStreamlineIcons;

use ErikGaal\BladeStreamlineIcons\Exceptions\IconAlreadyExistsException;
use ErikGaal\BladeStreamlineIcons\Exceptions\IconNotFoundException;
use ErikGaal\BladeStreamlineIcons\Exceptions\OptimizationNotAvailable;
use RuntimeException;

class BladeStreamlineIcons
{
    private array $familyAliases = [];

    public function __construct(
        private readonly StreamlineApi $api,
        private readonly Optimizer $optimizer = new Optimizer(),
    ) {
    }

    public function download(IconFamily $family, string $icon): string
    {
        $result = $this->api->search($family, $icon)->firstWhere('slug', $icon);

        if (! $result) {
            throw new IconNotFoundException("Could not find icon [$icon] in family [$family].");
        }

        return $this->api->download($result['hash']);
    }

    public function save(IconFamily $family, string $icon, string $path = null, bool $optimize = false, bool $overwrite = false): void
    {
        $basePath = config('blade-streamline-icons.path');
        $path = $this->joinPaths($basePath, $path);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (file_exists($path) && ! $overwrite) {
            throw new IconAlreadyExistsException("File [$path] already exists. Use --force to overwrite.");
        }

        $icon = $this->download(family: $family, icon: $icon);

        file_put_contents($path, $icon);

        if ($optimize) {
            if (! $this->optimizer->canOptimize()) {
                throw new OptimizationNotAvailable('Optimizing SVGs requires the `svgo` binary to be installed.');
            }

            $this->optimizer->optimize($path);
        }
    }

    public function family(string $name): IconFamily
    {
        if ($family = $this->familyAliases[$name] ?? null) {
            return new IconFamily(
                name: $family,
                alias: $name,
            );
        }

        return new IconFamily(name: $name);
    }

    public function addFamilyAlias(string $alias, string $family): void
    {
        $this->familyAliases[$alias] = $family;
    }

    private function joinPaths($basePath, $path = ''): string
    {
        return $basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}
