<?php

namespace ErikGaal\BladeStreamlineIcons\Commands;

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use Illuminate\Console\Command;

class GetCommand extends Command
{
    public $signature = 'streamline-icons:get {family} {icon}';

    public $description = 'Retrieve the Streamline icon';

    public function handle(BladeStreamlineIcons $streamline): int
    {
        $icon = $this->argument('icon');
        $family = $streamline->family($this->argument('family'));

        $result = $streamline->download($family, $icon);

        if (! $result) {
            $this->error("Unable to find icon [$icon] in family [$family]");

            return self::FAILURE;
        }

        $this->line($result);

        return self::SUCCESS;
    }
}
