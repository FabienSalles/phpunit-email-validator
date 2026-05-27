# https://tech.davis-hansson.com/p/make/

SHELL := bash
.ONESHELL:
.SHELLFLAGS := -eu -o pipefail -c
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules

DC := docker compose
RUN_PHP := $(DC) run --rm php

.DEFAULT_GOAL := help

help: ## Outputs this help screen.
	@grep -E '(^[a-zA-Z0-9_\-\/]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

php/cli: ## Enter PHP CLI.
	$(RUN_PHP) bash
.PHONY: php/cli
