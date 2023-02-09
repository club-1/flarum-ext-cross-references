all: vendor js;

dev: vendor js;

js:
	$(MAKE) -C $@ $(filter $(JSPHONY),$(MAKECMDGOALS))

vendor: composer.json composer.lock
	composer install
	touch $@

# Create a new release
release%: TAG = v$(shell js/node_modules/.bin/semver -i $* `git describe --tags --abbrev=0`)
release%: CONFIRM_MSG = Create release $(TAG)
releasepatch releaseminor releasemajor: release%: .confirm check all
	git add js/dist
	git commit -m $(TAG) --allow-empty
	git push
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

.confirm:
	@echo -n "$(CONFIRM_MSG)? [y/N] " && read ans && [ $${ans:-N} = y ]

JSPHONY = all dev check analyse clean
.PHONY: all dev js releasepatch releaseminor releasemajor check analyse test testunit testintegration clean cleancache .confirm
