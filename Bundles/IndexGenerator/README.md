# IndexGenerator Module

Postgres does not auto create indexes for foreign-key columns. This module brings a console command to create schema files with index definitions for all tables and their foreign-key columns which do not have an index definition.


## Installation

```
composer require spryker/index-generator
```

Add the IndexGeneratorConsole to your ConsoleDependencyProvider and check the console command help page with `vendor/bin/console propel:index-generator:generate -h`


## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)

