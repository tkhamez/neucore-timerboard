# Brave TimerBoard

## Install

* Copy `.env.dist` to `.env` and adjust values or set the corresponding environment variables in another way.
* Import `scripts/systems.sql`
* Execute `composer install`
* Create or update the database schema with `composer db:update`
* Execute `npm install`

### Heroku

* Add build packs:
```
heroku buildpacks:add heroku/php
heroku buildpacks:add heroku/nodejs
```
