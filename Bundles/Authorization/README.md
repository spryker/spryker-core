# Authorization Module

[![Latest Stable Version](https://poser.pugx.org/spryker/authorization/v/stable.svg)](https://packagist.org/packages/spryker/authorization)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Generic authorization module that can be used across different layers.

It provides the possibility to implement different authorization strategies using the `Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface`.

The general idea is that strategies have a name and when an authorization check is requested a strategy can be requested by name. This allows it to share a single strategy coming from one module between different modules and also makes it easy to change strategies.

An authorization request is composed in this way:

 * `AuthorizationRequestTransfer`
   * has one `AuthorizationIdentityTransfer`
   * has one `AuthorizationEntityTransfer`

The identity describes accessor and the entity the object the authoriziation is checked against.

An authorization check will return an `AuthorizationResponseTransfer`.

## Installation

```
composer require spryker/authorization
```

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
