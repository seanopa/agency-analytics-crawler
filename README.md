## AGENCY ANALYTICS CRAWLER DEMO

This is a demo of how to crawl a web link using php. The backend framework used is symfony and the front uses vue js.

### PreRequisites

- PHP ~ 8
- MySql or MariaDB
- Npm ~ 8.0.0

### Instructions

1. Checkout this repository and run composer install
2. Run npm install or yarn if that's your preference
3. Create required DB and add DATABASE_URL to .env.local
4. Run migrations `php bin/console doctrine:migrations:migrate --no-interation`
5. Install symfony-cli as instructed at https://symfony.com/download. See additional instructions below

$ `echo '[symfony-cli]
name=Symfony CLI
baseurl=https://repo.symfony.com/yum/
enabled=1
gpgcheck=0' | sudo tee /etc/yum.repos.d/symfony-cli.repo`

$ `sudo dnf install symfony-cli`

### Running local server

1. Run command `npm run watch`. This will create files in public/build
2. Run local server `symfony server:start`. Usually this will be served at https://127.0.0.1:8000

### Useful commands

* `php bin/console make:entity`
* `php bin/console make:entity --regenerate App`
* `php bin/console make:migration`
* `php bin/console doctrine:migrations:migrate`
* `bin/console messenger:consume amqp -vv`

### Consumers

If you decide to use a queue like RabbitMQ then run consumers as below. See ref: https://symfony.com/doc/current/the-fast-track/en/18-async.html

$ `symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async -vv`