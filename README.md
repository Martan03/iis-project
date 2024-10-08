# IIS project

## Table of Contents
- [Hosting the project on localhost](#hosting-the-project-on-localhost)
    - [Symfony server](#symfony-server)
    - [SQL server](#sql-server)
        - [Setting up the database](#setting-up-the-database)
- [Useful commands](#useful-commands)

## Hosting the project on localhost

### Symfony server

To run the symfony server, you should install `php`, `composer` and
`symfony-cli` packages. After it's done installing, you have to install the
project packages:
```bash
composer install
```

Then you can finally start the symfony server (`-d` starts server as daemon):
```bash
symfony server:start [-d]
```

### SQL server

You should also start the database server. I use `xampp`, which you can
download [here](https://www.apachefriends.org/download.html). To start the
SQL server, you can either use the GUI app, or you can start it from the
terminal (`xampp` might not be in path, on linux, it's `/opt/lampp/lampp`):
```bash
sudo xampp startmysql
```

#### Setting up the database

If the database isn't created yet, you can do it by executing this (you should
have SQL server running):
```bash
php bin/console doctrine:database:create
```

To actually create the tables in the database, you can use migrations. If the
migration is not generated yet, you can generate it like this:
```bash
symfony console make:migration
```

When you have the generated migration, you can migrate it, which create the
actual tables in the database based on the Entities in the project:
```bash
symfony console doctrine:migrations:migrate
```

If there are fixtures created, you can load them to the database:
```bash
symfony console doctrine:fixtures:load
```

## Useful commands

There's a list of some useful commands, which could come in handy:

| Function            | Command                           |
|---------------------|-----------------------------------|
| Create entity       | `symfony console make:entity`     |
| Create controller   | `symfony console make:controller` |
| Create form         | `symfony console make:form`       |
| Display all routers | `symfony console debug:router`    |
