<?php

namespace ErikGaal\BladeStreamlineIcons\Commands;

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SaveCommand extends Command
{
    public $signature = 'streamline-icons:save {family} {icon} {--as=} {--overwrite}';

    public $description = 'Save the Streamline icon';

    public function handle(BladeStreamlineIcons $streamline): int
    {
        $icon = $this->argument('icon');
        $family = $this->argument('family');

        $result = $streamline->download($family, $icon);

        if (! $result) {
            $this->error("Unable to find icon [$icon] in family [$family]");

            return self::FAILURE;
        }

        $path = $this->option('as') ?? "$family/$icon.svg";

        $this->save($result, $path);

        $this->info("Successfully saved icon [$icon] in family [$family] to [$path]");

        return self::SUCCESS;
    }

    private function save(string $icon, string $as): void
    {
        $basePath = config('blade-streamline-icons.path');
        $path = $this->joinPaths($basePath, $as);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (file_exists($path) && ! $this->option('force')) {
            throw new RuntimeException("File [$path] already exists. Use --force to overwrite.");
        }

        file_put_contents($path, $icon);
    }

    private function joinPaths($basePath, $path = ''): string
    {
        return $basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}
