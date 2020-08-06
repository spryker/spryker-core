# Payment Module
[![Build Status](https://travis-ci.org/spryker/payment.svg)](https://travis-ci.org/spryker/payment)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)

Payment is a gateway module which delegates calls to concrete payment modules and persists order payment. Spryker enables to have multiple payments per checkout. Each payment method must provide payment amount it shares from order grand total.

## Installation

```
composer require spryker/payment
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/checkout_process/payment.html)
