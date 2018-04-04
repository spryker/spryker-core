# SalesReclamation Module
[![Build Status](https://travis-ci.org/spryker/sales-reclamation.svg)](https://travis-ci.org/spryker/sales-reclamation)
[![Coverage Status](https://coveralls.io/repos/github/spryker/sales-reclamation/badge.svg)](https://coveralls.io/github/spryker/sales-reclamation)

Module for handling customer requests about orders. Operator can claim item or send another order.

Reclamtion -instrument for operator to handle customer request with order. In fact, he has 2 options:
* create new order
* refund items

Workflow.
- Create reclamation
    * Operator open order list
    * CHoose order and hit `claim`
    * Now operator see reclamation creation window
      * Operator select items 
      * operator hit `create reclamation`
    * Operator see reclamation view

- view reclamation
    * see order (link) from witch reclamation was created
        * inside sales section
    * see list of created (related) orders (see below)
    * list of all reclamation

- edit reclamation
    * close reclamation (once for all) from table.
    * refund item (once for all) from view
    * create order => manual order creation



## Installation

* add modules to composer
* all files in ` src/Orm/Zed`-> database
  * add new tables `SpySalesReclamation`
  * add new tables `SpySalesReclamationItem`
  * add file to ORM `https://github.com/spryker/demoshop-nonsplit/pull/920/files#diff-5b91725de11fd6e89eb3a542eea7dab8`
* add plugin to manual order creation https://github.com/spryker/demoshop-nonsplit/pull/920/files#diff-da9962f687c172ad43a104a15d6a633c
* add plugin to save order https://github.com/spryker/demoshop-nonsplit/pull/920/files#diff-3f699939e77a64dad4af535fded8ebd5
* add plugin to order list view https://github.com/spryker/demoshop-nonsplit/pull/920/files#diff-fd488b1ea52f267da7d3f504a1fee1c0

```
composer require spryker/sales-reclamation
```

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
