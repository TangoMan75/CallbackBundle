
# Run "make" to print help
# If you want to add a help message for your rule, just add : "## My help for this rule", on the previous line

.PHONY: help tests coverage composer-install uninstall

# Colors
COLOR_TITLE   = \033[1;41m
COLOR_CAPTION = \033[1;44m
COLOR_LABEL   = \033[1;32m
COLOR_WARNING = \033[33m
COLOR_INFO    = \033[35m
COLOR_PRIMARY = \033[36m
COLOR_DEFAULT = \033[0m

# get correct console executable
CONSOLE=$(shell if [ -f ./app/console ]; then echo './app/console'; elif [ -f ./bin/console ]; then echo './bin/console'; fi)
# get correct public folder
PUBLIC=$(shell if [ -d ./web ]; then echo './web'; elif [ -d ./public ]; then echo './public'; else echo './'; fi)

## Print this help
help:
	@printf "${COLOR_TITLE}TangoMan $(shell basename ${CURDIR}) ${COLOR_DEFAULT}\n\n"
	@printf "${COLOR_PRIMARY} date:${COLOR_DEFAULT}   ${COLOR_INFO}$(shell date '+%Y-%m-%d %H:%M:%S')${COLOR_DEFAULT}\n"
	@printf "${COLOR_PRIMARY} login:${COLOR_DEFAULT}  ${COLOR_INFO}$(shell whoami)${COLOR_DEFAULT}\n"
	@printf "${COLOR_PRIMARY} system:${COLOR_DEFAULT} ${COLOR_INFO}$(shell uname -s)${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}description:${COLOR_DEFAULT}\n\n"
	@printf "$(COLOR_WARNING) Callback Symfony Twig Extension Bundle${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}Usage:${COLOR_DEFAULT}\n\n"
	@printf "$(COLOR_WARNING) make test${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}Available commands:${COLOR_DEFAULT}\n\n"
	@awk '/^[a-zA-Z\-\_0-9\@]+:/ { \
		HELP_LINE = match(LAST_LINE, /^## (.*)/); \
		HELP_COMMAND = substr($$1, 0, index($$1, ":")); \
		HELP_MESSAGE = substr(LAST_LINE, RSTART + 3, RLENGTH); \
		printf " ${COLOR_LABEL}%-16s${COLOR_DEFAULT} ${COLOR_PRIMARY}%s${COLOR_DEFAULT}\n", HELP_COMMAND, HELP_MESSAGE; \
	} \
	{ LAST_LINE = $$0 }' ${MAKEFILE_LIST}

## Run tests
tests:
	@if [ ! -d ./vendor ]; then \
		make --no-print-directory composer-install; \
	fi
	@if [ -x ./bin/phpunit ]; then \
		php -d memory-limit=-1 ./bin/phpunit --stop-on-failure; \
	elif [ -x ./vendor/bin/phpunit ]; then \
		bash ./vendor/bin/phpunit --stop-on-failure; \
	elif [ -x ./vendor/bin/simple-phpunit ]; then \
		php -d memory-limit=-1 ./vendor/bin/simple-phpunit --stop-on-failure; \
	else \
		printf 'error: phpunit executable not found\n'; \
		exit 1; \
	fi

## Dump coverage (requires XDebug)
coverage:
	@if [ ! -d ./vendor ]; then \
		make --no-print-directory composer-install; \
	fi
	@if [ -x ./bin/phpunit ]; then \
		php -d memory-limit=-1 ./bin/phpunit --coverage-html ${PUBLIC}/coverage; \
	elif [ -x ./vendor/bin/phpunit ]; then \
		bash ./vendor/bin/phpunit --coverage-html ${PUBLIC}/coverage; \
	elif [ -x ./vendor/bin/simple-phpunit ]; then \
		php -d memory-limit=-1 ./vendor/bin/simple-phpunit --coverage-html ${PUBLIC}/coverage; \
	else \
		printf 'error: phpunit executable not found\n'; \
		exit 1; \
	fi
ifeq (${OS}, Windows_NT)
	@start "${PUBLIC}/coverage/index.html"
else
	@nohup xdg-open "${PUBLIC}/coverage/index.html" >/dev/null 2>&1
endif

## Composer install Symfony project
composer-install:
ifeq (${OS}, Windows_NT)
	composer install
else
	php -d memory-limit=-1 $(shell which composer) install
endif

## Remove vendors, var/cache, var/logs & var/sessions
uninstall:
	rm -f ./composer.lock
	rm -rf ./coverage
	rm -rf ./vendor
