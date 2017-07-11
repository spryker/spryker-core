# CustomerGroupDiscountConnector Module

## Installation

```
composer require spryker/customer-group-discount-connector
```

To enable `CustomerGroupDecisionRulePlugin` add it to `DiscountDependencyProvider` should be placed inside `getDecisionRulePlugins` method. 

Make sure you have latest changes for Yves side. There was issue when customer login, then `QuoteTransfer` may be missing `CustomerTransfer`.


## Documentation

[Documentation](https://spryker.github.io)
