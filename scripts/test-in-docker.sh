#!/usr/bin/env bash
# Runs static analysis and tests in a digest-pinned PHP image with networking disabled.
set -euo pipefail

image="php@sha256:f324fcb4dc009ac0470a7d614cd122499dcee3ca47a9bab620fc14c0eb759ba2"
root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

docker run --rm --network none --user "$(id -u):$(id -g)" \
  -v "$root:/app:ro" -v "$root/reports/tests:/app/reports/tests" -w /app "$image" \
  sh -c 'php -d memory_limit=-1 vendor/bin/phpstan analyse --no-progress --memory-limit=1G && php vendor/bin/phpunit --log-junit reports/tests/junit.xml --testdox-text reports/tests/testdox.txt'
