up :
	docker compose up -d

down :
	docker compose down

php :
	docker compose exec php bash

build :
	docker compose build

PHINX = docker compose exec -w /var/www/html/db php ../vendor/bin/phinx

migrate :
	$(PHINX) migrate

NODE = docker compose run --rm node

js :
	$(NODE) sh -c "npm ci && npm run build"

js-watch :
	$(NODE) npm run watch
