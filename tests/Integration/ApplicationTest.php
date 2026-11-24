<?php

declare(strict_types=1);

namespace Nav2018\Tests\Integration;

use Nav2018\Application;
use Nav2018\Http\Request;
use Nav2018\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public static function pages(): array
    {
        return [
            'home' => ['/', 'Home'],
            'two' => ['/section-two', 'Section Two'],
            'three' => ['/section-three', 'Section Three'],
            'four' => ['/section-four', 'Section Four'],
        ];
    }

    #[DataProvider('pages')]
    public function testRendersPageWithActiveNavigation(string $path, string $title): void
    {
        $response = $this->dispatch('GET', $path);

        self::assertSame(200, $response->status);
        self::assertStringContainsString('<html lang="en">', $response->body);
        self::assertStringContainsString("<h1>{$title}</h1>", $response->body);
        self::assertSame(1, substr_count($response->body, 'aria-current="page"'));
        self::assertStringContainsString("href=\"{$path}\" aria-current=\"page\"", $response->body);
    }

    public function testUnknownPathReturnsNotFoundWithoutReflectingInput(): void
    {
        $response = $this->dispatch('GET', '/<script>alert(1)</script>');

        self::assertSame(404, $response->status);
        self::assertStringNotContainsString('<script>', $response->body);
        self::assertStringNotContainsString('alert(1)', $response->body);
    }

    public function testRejectsUnsupportedMethod(): void
    {
        $response = $this->dispatch('POST', '/');

        self::assertSame(405, $response->status);
        self::assertSame('GET, HEAD', $response->headers['Allow']);
    }

    public function testHeadRequestHasNoBody(): void
    {
        $response = $this->dispatch('HEAD', '/');

        self::assertSame(200, $response->status);
        self::assertSame('', $response->body);
    }

    public function testEveryResponseCarriesSecurityHeaders(): void
    {
        foreach ([['GET', '/'], ['GET', '/missing'], ['POST', '/']] as [$method, $path]) {
            $headers = $this->dispatch($method, $path)->headers;

            self::assertStringContainsString("default-src 'none'", $headers['Content-Security-Policy']);
            self::assertSame('nosniff', $headers['X-Content-Type-Options']);
        }
    }

    private function dispatch(string $method, string $uri): Response
    {
        return Application::create(dirname(__DIR__, 2) . '/templates')->handle(new Request($method, $uri));
    }
}
