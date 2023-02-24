<?php

namespace ErikGaal\BladeStreamlineIcons;

use ErikGaal\BladeStreamlineIcons\Exceptions\IconAlreadyExistsException;

class BladeStreamlineIcons
{
    private array $familyAliases = [];

    public function __construct(
        private readonly StreamlineApi $api,
    ) {}

    public function download(IconFamily $family, string $icon): ?string
    {
        $icon = $this->api->search($family, $icon)->firstWhere('slug', $icon);

        if (! $icon) {
            return null;
        }

        return $this->api->download($icon['hash']);
    }

    public function save(IconFamily $family, string $icon, string $path = null, bool $overwrite = false): void
    {
        $basePath = config('blade-streamline-icons.path');
        $path = $this->joinPaths($basePath, $path);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (file_exists($path) && ! $overwrite) {
            throw new IconAlreadyExistsException("File [$path] already exists. Use --force to overwrite.");
        }

        $icon = $this->download($family, $icon);

        file_put_contents($path, $icon);
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
