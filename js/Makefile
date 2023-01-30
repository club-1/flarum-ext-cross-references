all: node_modules
	npm run build

dev: node_modules
	npm run dev

node_modules: package.json package-lock.json
	npm install
	touch $@

check: node_modules
	npm run check-typings

clean:
	rm -rf node_modules

%:
	$(MAKE) all

.PHONY: all dev check clean
