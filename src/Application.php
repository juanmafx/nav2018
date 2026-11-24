<?php

declare(strict_types=1);

namespace Nav2018;

use Nav2018\Http\Request;
use Nav2018\Http\Response;
use Nav2018\Http\SecurityHeaders;
use Nav2018\Navigation\NavigationMenu;
use Nav2018\Routing\PageCatalog;
use Nav2018\View\TemplateRenderer;

final readonly class Application
{
    private const array ALLOWED_METHODS = ['GET', 'HEAD'];

    public function __construct(
        private PageCatalog $catalog,
        private NavigationMenu $menu,
        private TemplateRenderer $renderer,
    ) {
    }

    public static function create(string $templateDirectory): self
    {
        $catalog = PageCatalog::default();

        return new self($catalog, new NavigationMenu($catalog), new TemplateRenderer($templateDirectory));
    }

    public function handle(Request $request): Response
    {
        $path = $request->path();

        if (!in_array($request->method, self::ALLOWED_METHODS, true)) {
            return $this->respond($request, 405, 'Method Not Allowed', $path, ['Allow' => implode(', ', self::ALLOWED_METHODS)]);
        }

        $page = $this->catalog->find($path);

        if ($page === null) {
            return $this->respond($request, 404, 'Page Not Found', $path);
        }

        return $this->respond($request, 200, $page->title, $path);
    }

    private function respond(Request $request, int $status, string $title, string $path, array $extraHeaders = []): Response
    {
        $navigation = $this->renderer->render('partials/navigation', ['links' => $this->menu->linksFor($path)]);
        $body = $this->renderer->render('layout', ['title' => $title, 'heading' => $title, 'navigation' => $navigation]);
        $headers = SecurityHeaders::all() + ['Content-Type' => 'text/html; charset=UTF-8'] + $extraHeaders;

        return new Response($status, $headers, $request->method === 'HEAD' ? '' : $body);
    }
}
