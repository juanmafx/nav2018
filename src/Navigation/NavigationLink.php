<?php

declare(strict_types=1);

namespace Nav2018\Navigation;

final readonly class NavigationLink
{
    public function __construct(
        public string $label,
        public string $path,
        public bool $isActive,
    ) {
    }
}
