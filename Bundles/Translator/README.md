# Translator Module
[![Build Status](https://travis-ci.org/spryker/translator.svg)](https://travis-ci.org/spryker/translator)
[![Coverage Status](https://coveralls.io/repos/github/spryker/translator/badge.svg)](https://coveralls.io/github/spryker/translator)

Translator extends symfony translator in order to collect translations from all Zed modules.
New translator is using via TranslatorTwigExtensionPlugin in order to plug-in it into service provider.
Module provides two new console commands in order to clear and generate translation cache.
```
./vendor/bin/console translator:clear-cache
./vendor/bin/console translator:generate-cache
```

## Installation

```
composer require spryker/translator
```

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
