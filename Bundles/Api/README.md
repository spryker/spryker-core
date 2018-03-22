# Api Module
[![Build Status](https://travis-ci.org/spryker/Api.svg)](https://travis-ci.org/spryker/Api)
[![Coverage Status](https://coveralls.io/repos/github/spryker/Api/badge.svg)](https://coveralls.io/github/spryker/Api)

Each of our modules can have an API module. Our API modules expose CRUD facade methods (find, get, add, update, remove) that can be mapped to a URL via REST resource/action resolution. We ship some of our crucial modules as showcases, but you can easily create more such modules if needed. The main API module contains a dispatcher that delegates to those API module via resource map and returns the response in the expected format.

## Installation

```
composer require spryker/api
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/zed_api/zed_api.html)
