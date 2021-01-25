# Price Module
[![Build Status](https://travis-ci.org/spryker/price.svg)](https://travis-ci.org/spryker/price)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Price handles product pricing. Price module also provides plugins for products to populate prices.
Prices are added to abstract and concrete products. The price is stored as an integer, in the smallest unit of the currency. Each price is assigned to a price type. Each product can have one or many prices with different price types.

## Installation

```
composer require spryker/price
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/products/price.html)

Old price module code moved to `spryker/price-product`.
