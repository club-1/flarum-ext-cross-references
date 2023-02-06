all: vendor js;

dev: vendor js;

js:
	$(MAKE) -C $@ $(filter $(JSPHONY),$(MAKECMDGOALS))

vendor: composer.json composer.lock
	composer install
	touch $@

check: analyse test js;

analyse: js;
#	vendor/bin/phpstan

test: testunit;
#test: testunit testintegration;

testunit testintegration: export XDEBUG_MODE=coverage
testunit testintegration: test%: vendor
	composer test:$* --  --coverage-cache=tests/.phpunit.cov.cache --coverage-text --coverage-clover=tests/.phpunit.$*.cov.xml

clean: js cleancache
	rm -rf vendor

cleancache:
	rm -rf tests/.phpunit*

JSPHONY = all dev check analyse clean
.PHONY: all dev js check analyse test testunit testintegration clean cleancache
