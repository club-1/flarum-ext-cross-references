all: node_modules
	npm run build

dev: node_modules
	npm run dev

node_modules: package.json package-lock.json
	npm install
	touch $@

check: analyse;

analyse: node_modules
	npm run check-typings

clean:
	rm -rf node_modules

.PHONY: all dev check analyse clean
