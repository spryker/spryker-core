# GiftCard Module
[![Build Status](https://travis-ci.org/spryker/GiftCard.svg)](https://travis-ci.org/spryker/GiftCard)
[![Coverage Status](https://coveralls.io/repos/github/spryker/GiftCard/badge.svg)](https://coveralls.io/github/spryker/GiftCard)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spryker/GiftCard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spryker/GiftCard/?branch=master)

## Installation

```
composer require spryker/gift-card
```

## Documentation

TODO: To be added



TODO remove explanations
## Explanations

### Payment method filtering
#### Why these filters? 
They provide a module-agnostic way to influence what is needed.
Also useful for Payment Control

#### StepEngine
Based on the quote, the payment methods need to be filtered.
The provider modules only provide subform plugins though. The filtering should not be based on this.
Thats why a collection of all Payment methods is created and put through a filter plugin stack, then mapped later 
to the appropriate forms. To achieve this, a provider for the subforms needs to be used, so that the FormCollection is only created when the data transfer (the quote)
is known.

### Shipment filter
Also to be module-agnostic, an extra filter stack is needed (the shipment methods should not know about gift cards)


 

