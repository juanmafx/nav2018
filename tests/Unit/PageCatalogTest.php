<?php

declare(strict_types=1);

namespace Nav2018\Tests\Unit;

use Nav2018\Routing\PageCatalog;
use PHPUnit\Framework\TestCase;

final class PageCatalogTest extends TestCase
{
    public function testDefaultCatalogListsPagesInNavigationOrder(): void
    {
        $paths = array_map(static fn ($page) => $page->path, PageCatalog::default()->all());

        self::assertSame(['/', '/section-two', '/section-three', '/section-four'], $paths);
    }

    public function testFindsKnownPage(): void
    {
        self::assertSame('Section Three', PageCatalog::default()->find('/section-three')?->title);
    }

    public function testReturnsNullForUnknownPage(): void
    {
        self::assertNull(PageCatalog::default()->find('/missing'));
    }
}
