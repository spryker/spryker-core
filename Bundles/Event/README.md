# Event Module
[![Latest Stable Version](https://poser.pugx.org/spryker/event/v/stable.svg)](https://packagist.org/packages/spryker/event)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Event module implements an Observer pattern where you can add hooks (events) to your code and allow other bundles to listen and react to those events. Event enables to create synchronous and asynchronous events: traditional synchronous where listeners are handled at the same time as they are dispatched, and asynchronous (queueable) where events are put into a queue and handled later by some queue service.

## Installation

```
composer require spryker/event
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/infrastructure/event/event.html)
