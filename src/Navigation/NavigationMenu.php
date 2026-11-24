<?php

declare(strict_types=1);

namespace Nav2018\Navigation;

use Nav2018\Routing\Page;
use Nav2018\Routing\PageCatalog;

final readonly class NavigationMenu
{
    public function __construct(private PageCatalog $catalog)
    {
    }

    public function linksFor(string $currentPath): array
    {
        return array_map(
            static fn (Page $page): NavigationLink => new NavigationLink($page->title, $page->path, $page->path === $currentPath),
            $this->catalog->all(),
        );
    }
}
