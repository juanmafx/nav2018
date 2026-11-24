-- Optional: stores automated test run results. The website itself uses no database.
-- Verified against SQLite by tests/Database/SchemaTest.php.

CREATE TABLE test_run (
    id INTEGER PRIMARY KEY,
    application_version VARCHAR(32) NOT NULL,
    php_version VARCHAR(32) NOT NULL,
    started_at TIMESTAMP NOT NULL,
    finished_at TIMESTAMP NOT NULL,
    CHECK (finished_at >= started_at)
);

CREATE TABLE test_case_result (
    id INTEGER PRIMARY KEY,
    test_run_id INTEGER NOT NULL,
    suite VARCHAR(64) NOT NULL,
    test_name VARCHAR(255) NOT NULL,
    outcome VARCHAR(16) NOT NULL CHECK (outcome IN ('passed', 'failed', 'skipped', 'error')),
    duration_ms INTEGER NOT NULL CHECK (duration_ms >= 0),
    FOREIGN KEY (test_run_id) REFERENCES test_run (id) ON DELETE CASCADE,
    UNIQUE (test_run_id, suite, test_name)
);

CREATE INDEX idx_test_case_result_outcome ON test_case_result (test_run_id, outcome);
