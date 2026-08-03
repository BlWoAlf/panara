DC = docker compose

up:        ; $(DC) up -d
down:      ; $(DC) down
build:     ; $(DC) build
logs:      ; $(DC) logs -f
sh:        ; $(DC) exec php bash
psql:      ; $(DC) exec db psql -U app -d app
console:   ; $(DC) exec php bin/console $(filter-out $@,$(MAKECMDGOALS))
migrate:   ; $(DC) exec php bin/console doctrine:migrations:migrate -n
test:      ; $(DC) exec php bin/phpunit
%:         ; @: