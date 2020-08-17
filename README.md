# Doctrine File Fixtures
[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]

## About the project
The library is designed to offer you a functionality of loading database fixtures from files. It supports different file formats. The library can be extended and adapted in your projects.

Current version is still in progress. The most important of non-supported (yet!) functionality is Many-To-Many relation. It will be implemented in next version. However, for simple model structure the library works well.

## Installation
The preferred method of installation is via Packagist and Composer. Installation is very simple:

1. Run the following command to install the package and add it as a requirement to your project's composer.json:
```shell script
composer require tbajorek/doctrine-file-fixtures
```

2. Add this line:
```php
Tbajorek\DoctrineFileFixturesBundle\DoctrineFileFixturesBundle::class => ['all' => true]
```
to array in `config/bundles.php` file in your project to register the bundle.

Library is provided with some default configuration, so it's not required to define it. However, you can change it to meet your expectations. Read more about configuration [HERE][configuration].

## Usage
The library is used from command line. It adds some commands to your project.
### Loading the fixtures
To load the fixtures from file to database please just execute the following command:
```shell script
bin/console doctrine:file-fixtures:load
```
**Options:**
* _--clear_ - if set, then database will be cleared before loading fixtures there


## Detailed informations
1. [Configuration][configuration]
2. [Fixture file formats][fixture_file_formats]
3. [Using with other ORM][other_orm]

## TODO
As this library is still being developed, there are some planed features to be implemented in next versions:
1. fixtures for Many-To-Many relation
2. export operation to allow create fixture file(s) based on data from database
3. composite identifier using more than one column

[badge-source]: http://img.shields.io/badge/source-tbajorek/doctrine--file--fixtures-blue.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-release]: https://img.shields.io/packagist/v/tbajorek/doctrine-file-fixtures.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/tbajorek/doctrine-file-fixtures.svg?style=flat-square

[source]: https://github.com/tbajorek/doctrine-file-fixtures
[license]: https://github.com/tbajorek/doctrine-file-fixtures/blob/master/LICENSE
[release]: https://packagist.org/packages/tbajorek/doctrine-file-fixtures
[downloads]: https://packagist.org/packages/tbajorek/doctrine-file-fixtures

[configuration]: https://github.com/tbajorek/doctrine-file-fixtures/blob/master/docs/1.configuration.md
[fixture_file_formats]: https://github.com/tbajorek/doctrine-file-fixtures/blob/master/docs/2.fixture_file_formats.md
[other_orm]: https://github.com/tbajorek/doctrine-file-fixtures/blob/master/docs/3.other_orm.md