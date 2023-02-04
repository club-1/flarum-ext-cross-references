all: vendor js;

dev: vendor js;

js:
	$(MAKE) -C $@ $(MAKECMDGOALS)

vendor: composer.json composer.lock
	composer install
	touch $@

check: js test;

test: testunit;
#test: testunit testintegration;

testunit testintegration: export XDEBUG_MODE=coverage
testunit testintegration: test%: vendor
	composer test:$* --  --coverage-cache=tests/.phpunit.cov.cache --coverage-text --coverage-clover=tests/.phpunit.$*.cov.xml

clean: js cleancache
	rm -rf vendor

cleancache:
	rm -rf tests/.phpunit*

%:
	$(MAKE) all

.PHONY: all dev js check checkphp test testunit clean
