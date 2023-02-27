<?php

namespace ErikGaal\BladeStreamlineIcons\Solutions;

use ErikGaal\BladeStreamlineIcons\BladeStreamlineIcons;
use Spatie\Ignition\Contracts\RunnableSolution;

class DownloadIconSolution implements RunnableSolution
{
    public function __construct(
        protected ?string $iconName = null
    )
    {
    }

    public function getSolutionActionDescription(): string
    {
        return 'Download the icon from the Streamline website and save it in your project. Make sure to run `php artisan streamline-icons:login` before running the solution!';
    }

    public function getRunButtonText(): string
    {
        return 'Download';
    }

    public function run(array $parameters = []): void
    {
        $icon = $parameters['iconName'];

        app(BladeStreamlineIcons::class)->save($icon);
    }

    public function getRunParameters(): array
    {
        return [
            'iconName' => $this->iconName,
        ];
    }

    public function getSolutionTitle(): string
    {
        return 'A Streamline icon is missing';
    }

    public function getSolutionDescription(): string
    {
        return '';
    }

    public function getDocumentationLinks(): array
    {
        return [];
    }
}
