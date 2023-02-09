all: vendor js;

dev: vendor js;

js:
	$(MAKE) -C $@ $(filter $(JSPHONY),$(MAKECMDGOALS))

vendor: composer.json composer.lock
	composer install
	touch $@

# Create a new release
.PHONY: release-patch release-minor release-major
release-%: TAG = v$(shell js/node_modules/.bin/semver -i $* `git describe --tags --abbrev=0`)
release-patch release-minor release-major: release-%: check all
	git add js/dist
	git commit -m $(TAG)
	git tag $(TAG)
	git push --tags

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
