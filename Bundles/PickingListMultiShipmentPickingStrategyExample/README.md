# PickingListMultiShipmentPickingStrategyExample Module
[![Latest Stable Version](https://poser.pugx.org/spryker/picking-list-multi-shipment-picking-strategy-example/v/stable.svg)](https://packagist.org/packages/spryker/picking-list-multi-shipment-picking-strategy-example)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)

This module provides an example of a picking list generation strategy. Picking list strategies are used to define where and how order items are being picked.

A custom picklist generation strategy can be implemented on a project level per warehouse. The default picklist generation strategy includes the ability to generate picklists by order shipments, where each order line is assigned to a unique picklist that contains all the items needed to fulfill that order. This also includes splitting orders into multiple picklists depending on the warehouse assigned to each order line.

Keep in mind that this is just an example, you are free to implement any business logic using a strategy that will reflect your actual business processes.

## Installation

```
composer require spryker/picking-list-multi-shipment-picking-strategy-example
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)

## Disclaimer

This module is provided as an *example* to demonstrate certain functionalities. It is not intended for use in production systems and should be treated solely as a learning resource and example of an implementation. Therefore, it is strongly recommended to thoroughly review, modify, and *adapt the code to suit your specific requirements* before deploying it in any production setting.

Please note that *no liabilities or warranties* are provided with this codebase. The authors and contributors of this project cannot be held responsible for any damages or issues that may arise from the use or misuse of this code. It is your responsibility to assess the suitability of the module for your use case and to ensure that proper testing, security measures, and safeguards are implemented when integrating it into any production system.

There is *no intention to maintain or update* this codebase on a regular base, and no guarantees are made regarding its functionality, security, or compatibility with future software environments.

*We encourage you to learn from this example*, understand the underlying principles, and *adapt the code to meet your specific needs*. Feel free to explore, experiment, and build upon this module to create robust and reliable solutions tailored to your specific requirements!
