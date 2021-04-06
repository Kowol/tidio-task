# Tidio task
[![CI](https://github.com/Kowol/tidio-task/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/Kowol/tidio-task/actions/workflows/ci.yml)

## Setup

### Docker
Run `docker-compose up -d` to setup docker containers

Run `docker exec -it php bash -c "composer install"` to install composer packages

Run `docker exec -it php bash -c "bin/console d:m:m --no-interaction"` to setup database

## Usage

### Create department
Use `docker exec -it php bash -c "bin/console company:department:create"` command and follow the instructions

### Create employee
Use `docker exec -it php bash -c "bin/console company:employee:create"` command and follow the instructions

### Generate report
Use `docker exec php bash -c "bin/console company:salary:report"` command and follow the instructions

### Tests
### Codeception
Use `docker exec php bash -c "bin/codecept run"` to run all test suites
