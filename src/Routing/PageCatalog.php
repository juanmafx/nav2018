<?php

declare(strict_types=1);

namespace Nav2018\Routing;

final readonly class PageCatalog
{
    private array $pages;

    public function __construct(Page ...$pages)
    {
        $this->pages = array_values($pages);
    }

    public static function default(): self
    {
        return new self(
            new Page('/', 'Home'),
            new Page('/section-two', 'Section Two'),
            new Page('/section-three', 'Section Three'),
            new Page('/section-four', 'Section Four'),
        );
    }

    public function all(): array
    {
        return $this->pages;
    }

    public function find(string $path): ?Page
    {
        foreach ($this->pages as $page) {
            if ($page->path === $path) {
                return $page;
            }
        }

        return null;
    }
}
