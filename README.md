# PinMigration

A database migration package based on Phinx for ThinkPHP.

    Note: This package is only compatible with ThinkPHP5.1 now.


## Installing

```shell
$ composer require codesinging/pin-migration -vvv
```

## Usage

### Initialization

After you installed this package, you should firstly initialize the configuration by run:

``` shell
php think migrate:init
```

This command will create a configuration file named `migration.php` in the config directory.

### Commands

| Command | Description |
| --------| ------------|
| `migrate:init` | Initialize migration configuration |
| `migrate:breakpoint` | Manage breakpoints |
| `migrate:create` | Create a new migration |
| `migrate:refresh` | Rollback and re-run all migrations |
| `migrate:reset` | Rollback all migrations |
| `migrate:rollback` | Rollback the last or to a specific migration |
| `migrate:run` | Migrate the database |
| `migrate:status` | Show migration status |
| `migrate:test` | Verify the configuration file |
| `seed:create` | Create a new database seeder |
| `seed:run` | Run database seeders |

## Documentation

- [Official, English](http://docs.phinx.org/en/latest/)
- [Chinese](https://tsy12321.gitbooks.io/phinx-doc/)

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/codesinging/pin-migration/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/codesinging/pin-migration/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT