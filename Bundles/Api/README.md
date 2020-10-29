# Api Module
[![Build Status](https://travis-ci.org/spryker/api.svg)](https://travis-ci.org/spryker/api)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Each of our modules can have an API module. Our API modules expose CRUD facade methods (find, get, add, update, remove) that can be mapped to a URL via REST resource/action resolution. We ship some of our crucial modules as showcases, but you can easily create more such modules if needed. The main API module contains a dispatcher that delegates to those API module via resource map and returns the response in the expected format.

## Installation

```
composer require spryker/api
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/zed_api/zed_api.html)
