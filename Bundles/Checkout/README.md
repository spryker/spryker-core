# Checkout Module
[![Latest Stable Version](https://poser.pugx.org/spryker/checkout/v/stable.svg)](https://packagist.org/packages/spryker/checkout)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg)](https://php.net/)

Checkout provides the infrastructure to handle checkout workflow for an order placement call. The checkout process creates a generic approach for step processing. Each step knows how to handle the form data, where to store data is and which conditions are required in order to be able proceed to next step.
StepProcess is the class that knows how to navigate through steps. Checkout contains a stack of steps provided during class creation. The instance of this class is created in CheckoutFactory and it’s used in the CheckoutController class. Each step has a corresponding controller action. Checkout extensively uses QuoteTransfer data object to populate data from customer and track the current state of the checkout.

## Installation

```
composer require spryker/checkout
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)
