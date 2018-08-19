
# Run "make" to print help
# If you want to add a help message for your rule, just add : "## My help for this rule", on the previous line
# You can give "make" arguments with this syntax: PARAMETER=VALUE

.PHONY: help

# Colors
COLOR_TITLE     = \033[1;41m
COLOR_CAPTION   = \033[1;44m
COLOR_LABEL     = \033[1;32m
COLOR_DANGER    = \033[31m
COLOR_SUCCESS   = \033[32m
COLOR_WARNING   = \033[33m
COLOR_SECONDARY = \033[34m
COLOR_INFO      = \033[35m
COLOR_PRIMARY   = \033[36m
COLOR_DEFAULT   = \033[0m

# Date / Time
DATE=$(shell date -I)
DATETIME=$(shell date '+%Y-%m-%d %H:%M:%S')
TIME=$(shell date -Ins)
TIMESTAMP=$(shell date +%Y%m%d%H%M%S)
EPOCH=$(shell date +%s)
DAY=$(shell LANG=C date +%A)

# Local vars
ifeq (${OS}, Windows_NT)
	SYSTEM=${OS}
else
	SYSTEM=$(shell uname -s)
endif

WHOAMI=$(shell whoami)

BASEDIR=$(shell basename `git rev-parse --show-toplevel 2>/dev/null` 2>/dev/null)
# get lastest tag from git repository
VERSION=$(shell echo `git describe --exact-match --abbrev=0 2>/dev/null`)

# parameters (usage parameter=value)
parameter?=''

## Print this help
help:
	@printf "${COLOR_TITLE}TangoMan ${BASEDIR} ${VERSION}${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_PRIMARY} date:${COLOR_DEFAULT}   ${COLOR_INFO}${DATETIME}${COLOR_DEFAULT}\n"
	@printf "${COLOR_PRIMARY} login:${COLOR_DEFAULT}  ${COLOR_INFO}${WHOAMI}${COLOR_DEFAULT}\n"
	@printf "${COLOR_PRIMARY} system:${COLOR_DEFAULT} ${COLOR_INFO}${SYSTEM}${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}description:${COLOR_DEFAULT}\n\n"
	@printf "$(COLOR_WARNING) Callback Symfony Twig Extension Bundle${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}Usage:${COLOR_DEFAULT}\n\n"
	@printf "$(COLOR_WARNING) make tests${COLOR_DEFAULT}\n\n"

	@printf "${COLOR_CAPTION}Available commands:${COLOR_DEFAULT}\n\n"
	@awk '/^[a-zA-Z\-\_0-9\@]+:/ { \
		HELP_LINE = match(LAST_LINE, /^## (.*)/); \
		HELP_COMMAND = substr($$1, 0, index($$1, ":")); \
		HELP_MESSAGE = substr(LAST_LINE, RSTART + 3, RLENGTH); \
		printf " ${COLOR_LABEL}%-10s${COLOR_DEFAULT} ${COLOR_PRIMARY}%s${COLOR_DEFAULT}\n", HELP_COMMAND, HELP_MESSAGE; \
	} \
	{ LAST_LINE = $$0 }' ${MAKEFILE_LIST}

## Run test suite
tests: update run

## Update composer dependencies
update:
ifeq (${OS}, Windows_NT)
	composer update
else
	php -d memory_limit=-1 `which composer` update
endif

## Run test suite
run:
ifeq (${OS}, Windows_NT)
	php -d memory_limit=-1 ./vendor/symfony/phpunit-bridge/bin/simple-phpunit --stop-on-failure
else
	php -d memory_limit=-1 ./vendor/bin/simple-phpunit --stop-on-failure
endif
