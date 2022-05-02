php bin/console make:entity

php bin/console make:entity --regenerate App

php bin/console make:migration

php bin/console doctrine:migrations:migrate

bin/console messenger:consume amqp -vv

Install symfony-cli as instructed at https://symfony.com/download
See instructions below

$ `echo '[symfony-cli]
name=Symfony CLI
baseurl=https://repo.symfony.com/yum/
enabled=1
gpgcheck=0' | sudo tee /etc/yum.repos.d/symfony-cli.repo`

$ `sudo dnf install symfony-cli`

Run consumers as below. See ref: https://symfony.com/doc/current/the-fast-track/en/18-async.html

$ `symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async -vv`