# DummyPayment Module
[![Build Status](https://travis-ci.org/spryker/dummy-payment.svg)](https://travis-ci.org/spryker/dummy-payment)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)

DummyPayment is a sample payment method that demonstrates a simple state machine which has a couple of states, commands and conditions. With that state machine it’s possible to trigger events for order items from Zed’s order detail page. It can be used as a starting point for new payment integrations. DummyPayment can also be used for testing checkout and order placement without actually performing transactions.

## Installation

```
composer require spryker/dummy-payment
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/checkout_process/dummy_payment.html)
