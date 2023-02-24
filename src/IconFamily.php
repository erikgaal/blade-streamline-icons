<?php

namespace ErikGaal\BladeStreamlineIcons;

class IconFamily
{
    public function __construct(
        public readonly string $name,
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
