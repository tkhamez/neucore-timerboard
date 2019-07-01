# Brave Collective TimerBoard

## Install

* Copy `.env.dist` to `.env` and adjust values or set the corresponding environment variables in another way.
* Execute `composer install`
* Create or update the database schema with `composer db:update`
* Import `scripts/systems.sql`

Set the php.ini settings `log_errors` and `error_log` to log errors. 

### Rebuild Frontend

* Execute `npm install`

### Heroku

* Add build packs:
```
heroku buildpacks:add heroku/php
heroku buildpacks:add heroku/nodejs
```
