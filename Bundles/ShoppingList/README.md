# ShoppingList Module
[![Build Status](https://travis-ci.org/spryker/shopping-list.svg)](https://travis-ci.org/spryker/shopping-list)
[![Coverage Status](https://coveralls.io/repos/github/spryker/shopping-list/badge.svg)](https://coveralls.io/github/spryker/shopping-list)

Shopping list provides infrastructure and functionality to handle multiple shopping lists for a customer account as well as manage shopping list items. A customer has a default shopping list, which will be created first time when there is a request made to manage its items. There is only one shopping list per customer by default, however one customer can have multiple named shopping lists, if required. The Shopping list is permanent and persists between sessions. Shopping list can be shared with other company users from the same business unit or with complete business units from the same company as the shopping list owner is assigned to.

## Installation

```
composer require spryker/shopping-list
```

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
