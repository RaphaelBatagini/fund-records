# Fund Records

## Data Model - ER Diagram
![ER Diagram](./fund-records.png)

## Acessing the application
- Application: http://localhost/
- Mailhog: http://localhost:8025/

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

### Start the application

```bash
docker compose up -d
```

### Create storage link

```bash
docker exec -it APP_CONTAINER_NAME sh -c "cd public && ln -s ../storage/app/public storage"
```

**Important:** Do not run the command `php artisan storage:link` on the host machine, it will create a symlink that will not work on the container.

### Run the migrations

```bash
docker exec APP_CONTAINER_NAME php artisan migrate
```

### Run tests

```bash
docker exec APP_CONTAINER_NAME php artisan test
```

## TODO
- Add documentation using Swagger
- Run tests on Github Actions and add a badge with its status to README.md
- Add more unit tests
- Create some feature tests