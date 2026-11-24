<?php

declare(strict_types=1);

namespace Nav2018\Http;

final readonly class Request
{
    public string $method;

    public function __construct(string $method, public string $uri)
    {
        $this->method = strtoupper($method);
    }

    public static function fromGlobals(array $server): self
    {
        $method = $server['REQUEST_METHOD'] ?? 'GET';
        $uri = $server['REQUEST_URI'] ?? '/';

        return new self(is_string($method) ? $method : 'GET', is_string($uri) ? $uri : '/');
    }

    public function path(): string
    {
        $path = preg_split('/[?#]/', $this->uri, 2)[0] ?? '/';
        $trimmed = rtrim($path, '/');

        return $trimmed === '' ? '/' : $trimmed;
    }
}
