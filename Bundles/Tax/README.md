# Tax Module
[![Latest Stable Version](https://poser.pugx.org/spryker/tax/v/stable.svg)](https://packagist.org/packages/spryker/tax)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg)](https://php.net/)

Tax module is responsible for handling tax rates that can be applied to products, product options and shipments. This module covers destination based tax calculation according to European standards. Taxes are administered in form of tax rates and tax sets. The tax sets can have different tax rates for each country defined in the shop. A tax set is defined by a name and is uniquely identified by an id. As its name says, it’s associated to a set of rates. A tax rate is defined by a name, a numeric rate value and it’s linked to a country.

## Installation

```
composer require spryker/tax
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)
