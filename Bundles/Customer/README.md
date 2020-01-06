# Customer Module
[![Build Status](https://travis-ci.org/spryker/customer.svg)](https://travis-ci.org/spryker/customer)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)

Customer provides functionality for basic CRUD operations for customer and address entities. In addition, it also handles advanced features for handling customer registration, validation, password reset, authorization and anonymization. To comply with legislation regarding personal information privacy, deleting a customer can be customer initiated or backend initiated. Snapshots of customer data in existing orders are not affected by this action. Deleting an account anonymizes customer information and address data. In the out-of-the box solution we also anonymize customer email address making it possible for the customer to return and register again with a completely new account.

## Installation

```
composer require spryker/customer
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/customer_management/customer/customer.html)
