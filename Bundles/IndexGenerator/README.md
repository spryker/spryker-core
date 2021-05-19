# IndexGenerator Module
[![Latest Stable Version](https://poser.pugx.org/spryker/index-generator/v/stable.svg)](https://packagist.org/packages/spryker/index-generator)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Postgres does not auto create indexes for foreign-key columns. This module provides a console command to create schema files with index definitions for all tables and their foreign-key columns which do not have an index definition.

## Installation

```
composer require spryker/index-generator
```

Add the IndexGeneratorConsole to your ConsoleDependencyProvider and check the console command help page with `vendor/bin/console propel:postgres-indexes:generate -h`

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)

