<?php

declare(strict_types=1);

namespace Nav2018\Tests\Database;

use PDO;
use PDOException;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[RequiresPhpExtension('pdo_sqlite')]
final class SchemaTest extends TestCase
{
    private PDO $database;

    protected function setUp(): void
    {
        $this->database = new PDO('sqlite::memory:', options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $this->database->exec('PRAGMA foreign_keys = ON');
        $this->database->exec((string) file_get_contents(dirname(__DIR__, 2) . '/db/schema.sql'));
        $this->database->exec("INSERT INTO test_run VALUES (1, '1.0.0', '8.5', '2026-01-01 00:00:00', '2026-01-01 00:00:01')");
    }

    public function testRejectsUnknownOutcome(): void
    {
        $this->expectException(PDOException::class);

        $this->database->exec("INSERT INTO test_case_result VALUES (1, 1, 'Unit', 'a', 'unknown', 1)");
    }

    public function testRejectsResultForMissingRun(): void
    {
        $this->expectException(PDOException::class);

        $this->database->exec("INSERT INTO test_case_result VALUES (1, 99, 'Unit', 'a', 'passed', 1)");
    }

    public function testRejectsRunFinishingBeforeItStarted(): void
    {
        $this->expectException(PDOException::class);

        $this->database->exec("INSERT INTO test_run VALUES (2, '1.0.0', '8.5', '2026-01-02 00:00:00', '2026-01-01 00:00:00')");
    }

    public function testDeletingRunCascadesToResults(): void
    {
        $this->database->exec("INSERT INTO test_case_result VALUES (1, 1, 'Unit', 'a', 'passed', 1)");
        $this->database->exec('DELETE FROM test_run WHERE id = 1');

        $statement = $this->database->prepare('SELECT COUNT(*) FROM test_case_result');
        $statement->execute();

        self::assertSame(0, (int) $statement->fetchColumn());
    }
}
