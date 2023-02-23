<?php

namespace ErikGaal\BladeStreamlineIcons;

class BladeStreamlineIcons
{
    public function __construct(
        private readonly StreamlineApi $api,
    ) {
        $this->path = resource_path('icons/streamline');
    }

    public function download(string $family, string $icon): ?string
    {
        $icon = $this->api->search($family, $icon)->firstWhere('slug', $icon);

        if (! $icon) {
            return null;
        }

        return $this->api->download($icon['hash']);
    }
}
