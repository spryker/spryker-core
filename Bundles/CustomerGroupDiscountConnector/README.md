# CustomerGroupDiscountConnector Module
[![Build Status](https://travis-ci.org/spryker/customer-group-discount-connector.svg)](https://travis-ci.org/spryker/customer-group-discount-connector)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

CustomerGroupDiscountConnector provides discount plugin. This allows to define decision rules for specific customer groups.

## Installation

```
composer require spryker/customer-group-discount-connector
```

To enable `CustomerGroupDecisionRulePlugin` add it to `DiscountDependencyProvider` should be placed inside `getDecisionRulePlugins` method.

Make sure you have latest changes for Yves side. There was issue when customer login, then `QuoteTransfer` may be missing `CustomerTransfer`.

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/customer_management/customer/customer.html)
