<?php

declare(strict_types=1);

namespace Nav2018\Tests\Unit;

use Nav2018\Navigation\NavigationMenu;
use Nav2018\Routing\PageCatalog;
use PHPUnit\Framework\TestCase;

final class NavigationMenuTest extends TestCase
{
    public function testMarksOnlyCurrentPageActive(): void
    {
        $links = (new NavigationMenu(PageCatalog::default()))->linksFor('/section-two');
        $active = array_values(array_filter($links, static fn ($link) => $link->isActive));

        self::assertCount(4, $links);
        self::assertCount(1, $active);
        self::assertSame('/section-two', $active[0]->path);
    }

    public function testMarksNothingActiveForUnknownPath(): void
    {
        $links = (new NavigationMenu(PageCatalog::default()))->linksFor('/missing');

        self::assertSame([], array_filter($links, static fn ($link) => $link->isActive));
    }
}
