# SF5DockerInstall


This repository is a personnal ready-to-use repository for [Symfony 5](https://symfony.com/) using [WSL2](https://docs.microsoft.com/fr-fr/windows/wsl/install-win10) and [Docker](https://www.docker.com/products/docker-desktop) interface.

## Bashrc
In order to simplify the use of docker, please enter the following line in your bashrc (sudo nano ~/.bashrc):
* alias dc='docker-compose --env-file .env $*'
* alias php='docker-compose -f ../docker/docker-compose.yml exec php-fpm php $*'
* alias composer='docker-compose -f ../docker/docker-compose.yml exec php-fpm composer $*'

*Note: Those alias are totally optionals, because they override your local commands (eg: composer)*

## Composer

Start to make: 

* cd &lt;project dir&gt;/src
* composer update

If you need to use composer like "composer require ...", you must be in the <project dir>/src .

## Npm

Start to make: 

* cd &lt;project dir&gt;/src
* npm install (or *yarn install*)

If you need to use composer like "npm install ...", you must be in the <project dir>/src .

*Note: Node is not brought with docker images, it should be install inside your WSL. More informations about installing node via [Nodesource](https://github.com/nodesource/distributions)*

## Docker
To start Docker, please make:

* cd &lt;project dir&gt;/docker
* docker-compose up -d  (or *dc up -d* if you use aliases)

To stop Docker, please make:
* docker-compose down (or *dc down* if you use aliases)

## Contributing

Pull request are welcome.
