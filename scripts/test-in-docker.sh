#!/usr/bin/env bash
# Runs static analysis and tests in a digest-pinned PHP image with networking disabled.
set -euo pipefail

image="php@sha256:f324fcb4dc009ac0470a7d614cd122499dcee3ca47a9bab620fc14c0eb759ba2"
root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
log="$(mktemp)"
trap 'rm -f "$log"' EXIT
status=0

docker run --rm --network none --user "$(id -u):$(id -g)" \
  -v "$root:/app:ro" -v "$root/reports/tests:/app/reports/tests" -w /app "$image" \
  sh -c 'php -d memory_limit=-1 vendor/bin/phpstan analyse --no-progress --memory-limit=1G && php vendor/bin/phpunit --log-junit reports/tests/junit.xml --testdox-text reports/tests/testdox.txt' \
  | tee "$log" || status=$?

{
  printf 'Command: scripts/test-in-docker.sh\n'
  printf 'Image: %s\n' "$image"
  printf 'Run at (UTC): %s\n' "$(date -u +%FT%TZ)"
  sed 's/\x1b\[[0-9;]*m//g' "$log" | grep -E '^(PHPUnit [0-9]|Runtime:|Configuration:|Time:|OK |OK,|Tests:|FAILURES!|ERRORS!)' || printf 'No PHPUnit result was produced.\n'
} > "$root/reports/tests/summary.txt"

exit "$status"
