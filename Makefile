all: vendor js;

dev: vendor js;

js:
	$(MAKE) -C $@ $(MAKECMDGOALS)

vendor: composer.json composer.lock
	composer install
	touch $@

clean: js
	rm -rf vendor

%:
	$(MAKE) all

.PHONY: all dev js clean
