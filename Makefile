export XDEBUG_MODE := coverage

lint-code-style:
	@ vendor/bin/php-cs-fixer fix --config=.php_cs.dist --allow-risky=yes --dry-run --stop-on-violation --diff --using-cache=no src tests

fix-code-style:
	@ vendor/bin/php-cs-fixer fix --config=.php_cs.dist --allow-risky=yes --verbose --using-cache=no src tests

test:
	vendor/bin/phpunit --coverage-text

stats-analyze:
	vendor/bin/phpstan analyse

verify-test:
	vendor/bin/infection --show-mutations
