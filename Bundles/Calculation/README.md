# Calculation Module
[![Build Status](https://travis-ci.org/spryker/calculation.svg)](https://travis-ci.org/spryker/calculation)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Calculation, is used to calculate quotes and totals displayed in the cart/checkout or when an order is placed. Calculation is also used to recalculate order totals after refund. The calculation module extensively uses plugins to inject calculation algorithms. You can extend the stack of calculator plugins by adding a new custom calculators (e.g. subtotal calculation).

## Installation

```
composer require spryker/calculation
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/checkout_process/calculation/calculation.html)
