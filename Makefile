DATE = $(shell date +%F)
REPO_URL = https://github.com/club-1/flarum-ext-cross-references
INTERACTIVE := $(shell [ -t 0 ] && echo 1)
PHPSTANFLAGS += $(if $(INTERACTIVE),,--no-progress) $(if $(INTERACTIVE)$(CI),,--error-format=raw)
PHPUNITFLAGS += $(if $(INTERACTIVE)$(CI),--coverage-text,--colors=never)

all: js vendor;

dev: js vendor;

js:
	$(MAKE) -C $@ $(filter $(JSPHONY),$(MAKECMDGOALS))

vendor: composer.json composer.lock
	composer install
	touch $@

# Create a new release
bump = echo '$2' | awk 'BEGIN{FS=OFS="."} {$$$1+=1} 1'
releasepatch: V := 3
releaseminor: V := 2
releasemajor: V := 1
release%: PREVTAG = $(shell git describe --tags --abbrev=0)
release%: TAG = v$(shell $(call bump,$V,$(PREVTAG:v%=%)))
release%: CONFIRM_MSG = Create release $(TAG)
releasepatch releaseminor releasemajor: release%: .confirm check all
	sed -i CHANGELOG.md \
		-e '/^## \[unreleased\]/s/$$/\n\n## [$(TAG)] - $(DATE)/' \
		-e '/^\[unreleased\]/{s/$(PREVTAG)/$(TAG)/; s#$$#\n[$(TAG)]: $(REPO_URL)/releases/tag/$(TAG)#}'
	git add CHANGELOG.md js/dist
	git commit -m $(TAG)
	git push
	git tag $(TAG)
	git push --tags

check: js analyse test;

analyse: js analysephp;

analysephp: vendor
	vendor/bin/phpstan analyse $(PHPSTANFLAGS)

test: testunit;
#test: testunit testintegration;

testunit testintegration: export XDEBUG_MODE=coverage
testunit testintegration: test%: vendor
	composer test:$* -- --coverage-cache=tests/.phpunit.cov.cache --coverage-clover=tests/.phpunit.$*.cov.xml $(PHPUNITFLAGS)

clean: js cleancache
	rm -rf vendor

cleancache:
	rm -rf tests/.phpunit*

.confirm:
	@echo -n "$(CONFIRM_MSG)? [y/N] " && read ans && [ $${ans:-N} = y ]

JSPHONY = all dev check analyse clean
.PHONY: all dev js releasepatch releaseminor releasemajor check analyse analysephp test testunit testintegration clean cleancache .confirm
