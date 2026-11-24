<?php

declare(strict_types=1);

namespace Nav2018\Routing;

final readonly class Page
{
    public function __construct(
        public string $path,
        public string $title,
    ) {
    }
}
