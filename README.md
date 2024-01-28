# Fund Records

## Requirements

- [Docker](https://docs.docker.com/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads)

## Setup

### Clone the repository

```bash
git clone git@github.com:RaphaelBatagini/fund-records.git
```

### Setup the environment

Copy ./src/.env.example to ./src.env

```bash
cp ./src/.env.example .env
```

### Install composer dependencies

```bash
docker compose run --rm app composer install
```

```bash
docker compose run --rm app composer dump-autoload
```

### Create storage link

```bash
docker exec -it APP_CONTAINER_NAME sh -c "cd public && ln -s ../storage/app/public storage"
```

**Important:** Do not run the command `php artisan storage:link` on the host machine, it will create a symlink that will not work on the container.

## Run the application

### Start the application

```bash
docker compose up -d
```

### Run migrations

```bash
docker exec APP_CONTAINER_NAME php artisan migrate
```

### Run seeds

```bash
docker exec APP_CONTAINER_NAME php artisan db:seed
```

### Run tests

```bash
docker exec APP_CONTAINER_NAME php artisan test
```

### Stop the application

```bash
docker compose down
```

### Run composer commands

Add new dependency

```bash
docker exec APP_CONTAINER_NAME composer require <dependency>
```

Update dependencies

```bash
docker exec APP_CONTAINER_NAME composer update
```

## Access the application

### Access through the browser

```bash
http://localhost:80
```

### Access the database

```bash
docker exec db bash
mysql -u laravel -p
```
