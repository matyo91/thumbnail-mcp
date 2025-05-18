NIX=nix develop --extra-experimental-features nix-command --extra-experimental-features flakes

##
##Dev
##-------------

nix: ## Start nix development
	$(NIX)

install: ## Install dependencies
	$(NIX) --command composer install

run: ## Run MCP server
	$(NIX) --command php mcp-server.php

generate-actions: ## Generate actions
	$(NIX) --command php bin/console app:generate-tools --action GMAIL_FETCH_EMAILS --action GOOGLECALENDAR_CREATE_EVENT --action GOOGLECALENDAR_FIND_EVENT --entityId matyo91

serve: ## Run Symfony development server
	$(NIX) --command ./bin/console server:start --port=8000

stop: ## Stop Symfony development server
	$(NIX) --command ./bin/console server:stop

# DEFAULT
.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

##
