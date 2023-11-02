# ProductWarehouseAllocationExample Module
[![Latest Stable Version](https://poser.pugx.org/spryker/product-warehouse-allocation-example/v/stable.svg)](https://packagist.org/packages/spryker/product-warehouse-allocation-example)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)

The strategy of this module is to simply find the first warehouse that has the required quantity of the items ordered. Based on the item stock, the warehouses are sorted in descending order. If the requested quantity of the item is available in the first warehouse, that is, the one holding the biggest stock of the item, this warehouse is assigned to fulfill the order item. The warehouse with the never out of stock item quantity is always assigned to the item.

Keep in mind that this is just an example, you are free to implement any business logic using a strategy that will reflect your actual business processes.

## Installation

```
composer require spryker/product-warehouse-allocation-example
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)

## Disclaimer

This module is provided as an *example* to demonstrate certain functionalities. It is not intended for use in production systems and should be treated solely as a learning resource and example of an implementation. Therefore, it is strongly recommended to thoroughly review, modify, and *adapt the code to suit your specific requirements* before deploying it in any production setting.

Note that *no liabilities or warranties* are provided with this codebase. The authors and contributors of this project cannot be held responsible for any damages or issues that may arise from the use or misuse of this code. It is your responsibility to assess the suitability of the module for your use case and to ensure that proper testing, security measures, and safeguards are implemented when integrating it into any production system.

*We encourage you to learn from this example*, understand the underlying principles, and *adapt the code to meet your specific needs*. Feel free to explore, experiment, and build upon this module to create robust and reliable solutions tailored to your specific requirements.
