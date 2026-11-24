<?php

declare(strict_types=1);

namespace Nav2018\Http;

final class SecurityHeaders
{
    public static function all(): array
    {
        return [
            'Content-Security-Policy' => "default-src 'none'; style-src 'self'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'",
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer',
            'Permissions-Policy' => 'camera=(), geolocation=(), microphone=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Cross-Origin-Resource-Policy' => 'same-origin',
        ];
    }
}
