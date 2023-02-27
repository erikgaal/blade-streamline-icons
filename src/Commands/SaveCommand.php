<?php

namespace ErikGaal\BladeStreamlineIcons\Commands;

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use ErikGaal\BladeStreamlineIcons\Exceptions\IconAlreadyExistsException;
use ErikGaal\BladeStreamlineIcons\Exceptions\IconNotFoundException;
use ErikGaal\BladeStreamlineIcons\Optimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SaveCommand extends Command
{
    public $signature = 'streamline-icons:save {family} {icon} {--as=} {--preserve} {--force}';

    public $description = 'Save the Streamline icon';

    public function handle(BladeStreamlineIcons $streamline, Optimizer $optimizer): int
    {
        $icon = $this->argument('icon');
        $family = $streamline->family($this->argument('family'));

        $name = $family . '/' . ($this->option('as') ?? $icon);
        $path = $name . ".svg";

        try {
            $streamline->save(
                family: $family,
                icon: $icon,
                path: $path,
                optimize: ! $this->option('preserve'),
                overwrite: $this->option('force')
            );
        } catch (IconNotFoundException) {
            $this->error("Unable to find icon [$icon] in family [$family]");

            return self::FAILURE;
        } catch (IconAlreadyExistsException) {
            $this->error("Icon [$icon] in family [$family] already exists. Use --force to overwrite.");

            return self::FAILURE;
        } catch (OptimizationNotAvailable) {
            $this->error("Optimizing SVGs requires the `svgo` binary to be installed. Use --preserve to skip optimization.");

            return self::FAILURE;
        }

        $this->info("Successfully saved icon [$icon] in family [$family] to [$path]");

        return self::SUCCESS;
    }
}
