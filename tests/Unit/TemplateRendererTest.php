<?php

declare(strict_types=1);

namespace Nav2018\Tests\Unit;

use InvalidArgumentException;
use Nav2018\View\Html;
use Nav2018\View\TemplateRenderer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TemplateRendererTest extends TestCase
{
    public static function invalidNames(): array
    {
        return [
            'parent traversal' => ['../secret'],
            'nested traversal' => ['partials/../../secret'],
            'absolute' => ['/etc/passwd'],
            'null byte' => ["layout\0"],
            'extension' => ['layout.php'],
            'trailing newline' => ["layout\n"],
            'empty' => [''],
        ];
    }

    #[DataProvider('invalidNames')]
    public function testRejectsUnsafeTemplateNames(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new TemplateRenderer(dirname(__DIR__, 2) . '/templates'))->render($name);
    }

    public function testDoesNotRenderExistingFileOutsideTemplateDirectory(): void
    {
        $root = sys_get_temp_dir() . '/' . uniqid('nav2018-', true);
        mkdir($root . '/templates', 0700, true);
        file_put_contents($root . '/outside.php', 'outside');

        try {
            (new TemplateRenderer($root . '/templates'))->render('../outside');
            self::fail('Traversal was not rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Invalid template name.', $exception->getMessage());
        } finally {
            unlink($root . '/outside.php');
            rmdir($root . '/templates');
            rmdir($root);
        }
    }

    public function testRejectsUnknownTemplate(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new TemplateRenderer(dirname(__DIR__, 2) . '/templates'))->render('missing');
    }

    public function testEscapesHtmlMetacharacters(): void
    {
        self::assertSame('&lt;a href=&quot;x&quot;&gt;&apos;&amp;', Html::escape('<a href="x">\'&'));
    }

    public function testLayoutEscapesTitleAndHeading(): void
    {
        $html = (new TemplateRenderer(dirname(__DIR__, 2) . '/templates'))->render('layout', [
            'title' => '<script>',
            'heading' => '<img>',
            'navigation' => '',
        ]);

        self::assertStringNotContainsString('<script>', $html);
        self::assertStringNotContainsString('<img>', $html);
    }
}
