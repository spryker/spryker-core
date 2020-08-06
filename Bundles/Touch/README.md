# Touch Module
[![Build Status](https://travis-ci.org/spryker/touch.svg)](https://travis-ci.org/spryker/touch)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)

As Yves has no connection to the database of Zed, touch is a key concept to make rendering front-end pages fast. Yves fetches all dynamic data from a key-value storage (Redis) and a search engine (Elasticsearch). The process of collecting data consists of two steps: touch and export. Touch module marks items for exporting to key-value storage and search.

## Installation

```
composer require spryker/touch
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
