<?php

declare(strict_types=1);

namespace Nav2018\View;

use InvalidArgumentException;

final readonly class TemplateRenderer
{
    private const string NAME_PATTERN = '/\A[a-z]+(?:-[a-z]+)*(?:\/[a-z]+(?:-[a-z]+)*)*\z/';

    public function __construct(private string $directory)
    {
    }

    public function render(string $template, array $variables = []): string
    {
        if (preg_match(self::NAME_PATTERN, $template) !== 1) {
            throw new InvalidArgumentException('Invalid template name.');
        }

        $file = $this->directory . '/' . $template . '.php';

        if (!is_file($file)) {
            throw new InvalidArgumentException('Unknown template.');
        }

        $render = static function (string $__file, array $__variables): void {
            extract($__variables, EXTR_SKIP);
            require $__file;
        };

        ob_start();

        try {
            $render($file, $variables);

            return (string) ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }
}
