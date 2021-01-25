# Availability Module
[![Build Status](https://travis-ci.org/spryker/availability.svg)](https://travis-ci.org/spryker/availability)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Product availability is calculated based on the current stock and amount of reserved items (items in the current open orders). Availability module calculates the ProductAbstract and ProductConcrete availability, and the calculated availability is persisted. This calculations is crucial to prevent overselling.

## Installation

```
composer require spryker/availability
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/inventory/availability.html)
