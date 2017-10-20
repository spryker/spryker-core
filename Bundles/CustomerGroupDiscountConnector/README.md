# CustomerGroupDiscountConnector Module

CustomerGroupDiscountConnector provides discount plugin. This allows to define decision rules for specific customer groups.

## Installation

```
composer require spryker/customer-group-discount-connector
```

To enable `CustomerGroupDecisionRulePlugin` add it to `DiscountDependencyProvider` should be placed inside `getDecisionRulePlugins` method. 

Make sure you have latest changes for Yves side. There was issue when customer login, then `QuoteTransfer` may be missing `CustomerTransfer`.


## Documentation

[Module Documentation](http://academy.spryker.com/developing_with_spryker/module_guide/customer_management/customer/customer.html)
