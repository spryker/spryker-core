# Discount Module

Discount module allows to create dynamic rules with which discounts can be applied to cart items. 
Discounts can be exclusive or nonexclusive. Exclusive discounts cannot be combined with other discounts. In case if multiple exclusive discounts are applicable, only the one with the highest discounted value will be applied.
Validity dates of the discount allows to make sure that the discount is valid for only the defined period of time. 
There are two type of discounts: cart rules and voucher code discounts. Cart rule discounts are not linked to a voucher pool, they are contained in the cart and are calculated automatically. Voucher code discounts are discounts that are linked to a voucher pool. In order to be applied, the associated voucher code must be entered by the customer. Once the voucher code is submitted, the calculation of the discount is being performed.

## Installation

```
composer require spryker/discount
```

## Documentation

[Module Documentation](http://academy.spryker.com/developing_with_spryker/module_guide/discount/discount.html)
