# CustomerGroupDiscountConnector Module
[![Latest Stable Version](https://poser.pugx.org/spryker/customer-group-discount-connector/v/stable.svg)](https://packagist.org/packages/spryker/customer-group-discount-connector)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg)](https://php.net/)

CustomerGroupDiscountConnector provides discount plugin. This allows to define decision rules for specific customer groups.

## Installation

```
composer require spryker/customer-group-discount-connector
```

To enable `CustomerGroupDecisionRulePlugin` add it to `DiscountDependencyProvider` should be placed inside `getDecisionRulePlugins` method.

Make sure you have latest changes for Yves side. There was issue when customer login, then `QuoteTransfer` may be missing `CustomerTransfer`.

## Documentation

[Spryker Documentation](https://docs.spryker.com)
