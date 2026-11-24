<?php

declare(strict_types=1);

namespace Nav2018\Tests\Unit;

use Nav2018\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public static function uris(): array
    {
        return [
            'root' => ['/', '/'],
            'plain' => ['/section-two', '/section-two'],
            'trailing slash' => ['/section-two/', '/section-two'],
            'query string' => ['/section-two?a=1', '/section-two'],
            'fragment' => ['/section-two#top', '/section-two'],
            'empty' => ['', '/'],
        ];
    }

    #[DataProvider('uris')]
    public function testNormalizesPath(string $uri, string $expected): void
    {
        self::assertSame($expected, (new Request('GET', $uri))->path());
    }

    public function testUppercasesMethod(): void
    {
        self::assertSame('POST', (new Request('post', '/'))->method);
    }

    public function testFallsBackWhenServerValuesAreMissingOrInvalid(): void
    {
        $request = Request::fromGlobals(['REQUEST_METHOD' => ['GET'], 'REQUEST_URI' => 42]);

        self::assertSame('GET', $request->method);
        self::assertSame('/', $request->uri);
    }
}
