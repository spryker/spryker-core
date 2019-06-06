# Container Module
[![Build Status](https://travis-ci.org/spryker/container.svg)](https://travis-ci.org/spryker/container)
[![Coverage Status](https://coveralls.io/repos/github/spryker/container/badge.svg)](https://coveralls.io/github/spryker/container)

Container module includes new service container class that is used instead of Pimple container in `spryker/kernel` module.


## Installation

```
composer require spryker/container
```

## Conflicts

- `spryker/pimple` Please remove spryker/pimple it is no longer needed and not supported anymore. This module is a replacement for Pimple.
- `pimple/pimple` Please remove pimple/pimple it is no longer supported. This module is a replacement for Pimple.
- `silex/silex` Please remove silex/silex it is no longer supported. Please use spryker/silex in the latest version.

When those modules aren't defined dependencies in your composer.json run on console `composer why vendor/package` to get information about which package requires those.

Please make sure that none of the mentioned modules exists in your project.


## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
