<?php

namespace ErikGaal\BladeStreamlineIcons;

class IconFamily
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $alias = null,
    ) {}

    public function __toString(): string
    {
        return $this->alias ?? $this->name;
    }
}
