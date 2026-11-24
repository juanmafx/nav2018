<?php

declare(strict_types=1);

namespace Nav2018\Tests\Functional;

use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HttpServerTest extends TestCase
{
    private static mixed $process = null;
    private static int $port = 0;

    public static function setUpBeforeClass(): void
    {
        $probe = stream_socket_server('tcp://127.0.0.1:0');

        if ($probe === false) {
            throw new RuntimeException('Could not reserve a port.');
        }

        self::$port = (int) substr((string) strrchr((string) stream_socket_get_name($probe, false), ':'), 1);
        fclose($probe);

        self::$process = proc_open(
            [PHP_BINARY, '-S', '127.0.0.1:' . self::$port, '-t', dirname(__DIR__, 2) . '/public'],
            [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes,
        );

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $socket = @fsockopen('127.0.0.1', self::$port, $code, $message, 0.1);

            if ($socket !== false) {
                fclose($socket);

                return;
            }

            usleep(50_000);
        }

        throw new RuntimeException('Server did not start.');
    }

    public static function tearDownAfterClass(): void
    {
        if (is_resource(self::$process)) {
            proc_terminate(self::$process);
            proc_close(self::$process);
        }
    }

    public function testServesPageOverHttpWithSecurityHeaders(): void
    {
        [$status, $headers, $body] = $this->fetch('/section-two');

        self::assertSame(200, $status);
        self::assertStringContainsString('<h1>Section Two</h1>', $body);
        self::assertStringContainsStringIgnoringCase("content-security-policy: default-src 'none'", $headers);
        self::assertStringContainsStringIgnoringCase('x-content-type-options: nosniff', $headers);
    }

    public function testServesStylesheetAllowedByPolicy(): void
    {
        [$status, $headers] = $this->fetch('/assets/site.css');

        self::assertSame(200, $status);
        self::assertStringContainsStringIgnoringCase('content-type: text/css', $headers);
    }

    public function testUnknownRouteReturnsNotFound(): void
    {
        [$status] = $this->fetch('/missing');

        self::assertSame(404, $status);
    }

    private function fetch(string $path): array
    {
        $socket = fsockopen('127.0.0.1', self::$port, $code, $message, 2.0);

        if ($socket === false) {
            throw new RuntimeException('Connection failed.');
        }

        fwrite($socket, "GET {$path} HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n");
        $raw = (string) stream_get_contents($socket);
        fclose($socket);
        [$headers, $body] = explode("\r\n\r\n", $raw, 2) + [1 => ''];

        return [(int) substr($headers, 9, 3), $headers, $body];
    }
}
