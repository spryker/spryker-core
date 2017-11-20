# DiscountPromotion Module
[![Build Status](https://travis-ci.org/spryker/DiscountPromotion.svg)](https://travis-ci.org/spryker/DiscountPromotion)
[![Coverage Status](https://coveralls.io/repos/github/spryker/DiscountPromotion/badge.svg)](https://coveralls.io/github/spryker/DiscountPromotion)

DiscountPromotion extends the Discount module with product promotion functionality. Discount promotions adds the ability to give away discounted or even free products to create promotions like "buy one, get one for free", "buy product X, get product Y for free", "buy 10 of product X and get 1 of product X for discounted price", etc.
This module extends Zed Administration Interface for discount with different discount collector type where you can define promotion product SKU and quantity. Cart page in Yves will show promotion products when conditions are satisfied. The quantity is the maximum number of products that can be added to cart with the promotion.

## Installation

```
composer require spryker/discount-promotion
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/discount/discount_promotion.html)
