<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Container;

use ArrayAccess;
use SplObjectStorage;
use Spryker\Service\Container\Exception\AliasException;
use Spryker\Service\Container\Exception\ContainerException;
use Spryker\Service\Container\Exception\FrozenServiceException;
use Spryker\Service\Container\Exception\NotFoundException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Container implements ContainerInterface, ArrayAccess
{
    public const TRIGGER_ERROR = 'container_trigger_error';

    /**
     * @var bool|null
     */
    protected $isTriggerErrorEnabled;

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var mixed[]
     */
    protected $raw = [];

    /**
     * @var bool[]
     */
    protected $serviceIdentifier = [];

    /**
     * @var array
     */
    protected static $globalServices = [];

    /**
     * @var array
     */
    protected static $globalServiceIdentifier = [];

    /**
     * @var bool[]
     */
    protected static $globalFrozenServices = [];

    /**
     * @var \SplObjectStorage
     */
    protected $factoryServices;

    /**
     * @var \SplObjectStorage
     */
    protected $protectedServices;

    /**
     * @var bool[]
     */
    protected $frozenServices = [];

    /**
     * This is a storage for services which should be extended, but at the point where extend was called the service was not found.
     *
     * @var array
     */
    protected $toBeExtended = [];

    /**
     * @var string|null
     */
    protected $currentlyExtending;

    /**
     * @var string|null
     */
    protected $currentExtendingHash;

    /**
     * @var array
     */
    protected $sharedServiceHashes = [];

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver|null
     */
    protected $configurationResolver;

    /**
     * @var string[]
     */
    protected static $aliases = [];

    /**
     * @param array $services
     */
    public function __construct(array $services = [])
    {
        if ($this->factoryServices === null) {
            $this->factoryServices = new SplObjectStorage();
        }

        if ($this->protectedServices === null) {
            $this->protectedServices = new SplObjectStorage();
        }

        foreach ($services as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $id
     * @param mixed $service
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     *
     * @return void
     */
    public function set(string $id, $service): void
    {
        if (isset($this->frozenServices[$id])) {
            throw new FrozenServiceException(sprintf('The service "%s" is frozen (already in use) and can not be changed at this point anymore.', $id));
        }

        $this->services[$id] = $service;
        $this->serviceIdentifier[$id] = true;

        if ($this->currentlyExtending === $id) {
            return;
        }

        $this->extendService($id, $service);
    }

    /**
     * @param string $id
     * @param callable|string $service
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     *
     * @return void
     */
    public function setGlobal(string $id, $service): void
    {
        if (isset(static::$globalFrozenServices[$id])) {
            throw new FrozenServiceException(sprintf('The global service "%s" is frozen (already in use) and can not be changed at this point anymore.', $id));
        }

        static::$globalServices[$id] = $service;
        static::$globalServiceIdentifier[$id] = true;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        $id = $this->getServiceIdentifier($id);

        return ($this->hasService($id) || $this->hasGlobalService($id));
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function hasService(string $id): bool
    {
        return isset($this->serviceIdentifier[$id]);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function hasGlobalService(string $id): bool
    {
        return isset(static::$globalServiceIdentifier[$id]);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get($id)
    {
        $id = $this->getServiceIdentifier($id);

        if ($this->hasGlobalService($id)) {
            return $this->getGlobalService($id);
        }

        return $this->getService($id);
    }

    /**
     * @param string $id
     *
     * @return string
     */
    protected function getServiceIdentifier(string $id): string
    {
        if (isset(static::$aliases[$id])) {
            return static::$aliases[$id];
        }

        return $id;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function getGlobalService(string $id)
    {
        if (!is_object(static::$globalServices[$id]) || !method_exists(static::$globalServices[$id], '__invoke')) {
            return static::$globalServices[$id];
        }

        $rawService = static::$globalServices[$id];
        $resolvedService = static::$globalServices[$id] = $rawService($this);

        static::$globalFrozenServices[$id] = true;

        return $resolvedService;
    }

    /**
     * @param string $id
     *
     * @throws \Spryker\Service\Container\Exception\NotFoundException
     *
     * @return mixed
     */
    protected function getService(string $id)
    {
        if (!$this->hasService($id)) {
            throw new NotFoundException(sprintf('The requested service "%s" was not found in the container!', $id));
        }

        if (isset($this->raw[$id])
            || !is_object($this->services[$id])
            || isset($this->protectedServices[$this->services[$id]])
            || !method_exists($this->services[$id], '__invoke')
        ) {
            return $this->services[$id];
        }

        if (isset($this->factoryServices[$this->services[$id]])) {
            return $this->services[$id]($this);
        }

        $rawService = $this->services[$id];
        $resolvedService = $this->services[$id] = $rawService($this);
        $this->raw[$id] = $rawService;

        $this->frozenServices[$id] = true;

        return $resolvedService;
    }

    /**
     * @param string $id
     * @param array $configuration
     *
     * @throws \Spryker\Service\Container\Exception\NotFoundException
     *
     * @return void
     */
    public function configure(string $id, array $configuration): void
    {
        if (!$this->hasService($id)) {
            throw new NotFoundException(sprintf('Only services which are added to the container can be configured! The service "%s" was not found.', $id));
        }

        $configuration = $this->getConfigurationResolver()
            ->resolve($configuration);

        if ($configuration['isGlobal']) {
            $this->makeGlobal($id);
        }

        $this->addAliases($id, $configuration['alias']);
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function getConfigurationResolver(): OptionsResolver
    {
        if ($this->configurationResolver === null) {
            $this->configurationResolver = new OptionsResolver();
            $this->configurationResolver
                ->setDefaults([
                    'isGlobal' => false,
                    'alias' => [],
                ])
                ->setAllowedTypes('isGlobal', ['bool'])
                ->setAllowedTypes('alias', ['array', 'string'])
                ->setNormalizer('alias', function (Options $options, $value) {
                    return (array)$value;
                });
        }

        return $this->configurationResolver;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    protected function makeGlobal(string $id): void
    {
        if (!$this->hasGlobalService($id)) {
            $rawService = $this->services[$id];
            $this->setGlobal($id, $rawService);
        }
    }

    /**
     * @param string $id
     * @param array $aliases
     *
     * @return void
     */
    protected function addAliases(string $id, array $aliases): void
    {
        foreach ($aliases as $alias) {
            $this->addAlias($id, $alias);
        }
    }

    /**
     * @param string $id
     * @param string $alias
     *
     * @throws \Spryker\Service\Container\Exception\AliasException
     *
     * @return void
     */
    protected function addAlias(string $id, string $alias): void
    {
        if (isset(static::$aliases[$alias]) && static::$aliases[$alias] !== $id) {
            throw new AliasException(sprintf(
                'The alias "%s" is already in use for the "%s" service and can\'t be reused for the service "%s".',
                $alias,
                static::$aliases[$alias],
                $id
            ));
        }

        static::$aliases[$alias] = $id;
    }

    /**
     * Do not set the returned callable to the Container, this is done automatically.
     *
     * @param string $id
     * @param \Closure $service
     *
     * @throws \Spryker\Service\Container\Exception\ContainerException
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     *
     * @return \Closure
     */
    public function extend(string $id, $service)
    {
        if ($this->hasGlobalService($id)) {
            return $this->extendGlobalService($id, $service);
        }

        if (!$this->hasService($id)) {
            // For BC reasons we will not throw exception here until everything is migrated.
            // We store the extension until the service is set and do the extension than.
            $this->extendLater($id, $service);

            $this->currentExtendingHash = spl_object_hash($service);

            return $service;
        }

        if (!is_object($service) || !method_exists($service, '__invoke')) {
            throw new ContainerException('The passed service for extension is not a closure and is not invokable.');
        }

        if (isset($this->frozenServices[$id])) {
            throw new FrozenServiceException(sprintf('The service "%s" is marked as frozen an can\'t be extended at this point.', $id));
        }

        if (!is_object($this->services[$id]) || !method_exists($this->services[$id], '__invoke')) {
            throw new ContainerException(sprintf('The requested service "%s" is not an object and is not invokable.', $id));
        }

        if (isset($this->protectedServices[$this->services[$id]])) {
            throw new ContainerException(sprintf('The requested service "%s" is protected and can\'t be extended.', $id));
        }

        $factory = $this->services[$id];

        $extended = function ($container) use ($service, $factory) {
            return $service($factory($container), $container);
        };

        if (isset($this->factoryServices[$factory])) {
            $this->factoryServices->detach($factory);
            $this->factoryServices->attach($extended);
        }

        $this->set($id, $extended);

        return $extended;
    }

    /**
     * Do not set the returned callable to the Container, this is done automatically.
     *
     * @param string $id
     * @param \Closure $service
     *
     * @throws \Spryker\Service\Container\Exception\ContainerException
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     *
     * @return \Closure
     */
    protected function extendGlobalService(string $id, $service)
    {
        if (!is_object($service) || !method_exists($service, '__invoke')) {
            throw new ContainerException('The passed service for extension is not a closure and is not invokable.');
        }

        if (isset(static::$globalFrozenServices[$id])) {
            throw new FrozenServiceException(sprintf('The global service "%s" is marked as frozen an can\'t be extended at this point.', $id));
        }

        if (!is_object(static::$globalServices[$id]) || !method_exists(static::$globalServices[$id], '__invoke')) {
            throw new ContainerException(sprintf('The requested service "%s" is not an object and is not invokable.', $id));
        }

        $factory = static::$globalServices[$id];

        $extended = function ($container) use ($service, $factory) {
            return $service($factory($container), $container);
        };

        $this->setGlobal($id, $extended);

        return $extended;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->removeAliases($id);
        $this->removeService($id);
        $this->removeGlobalService($id);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    protected function removeAliases(string $id): void
    {
        foreach ($this->getAliasesForService($id) as $alias) {
            unset(static::$aliases[$alias]);
        }

        unset(static::$aliases[$id]);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    protected function getAliasesForService(string $id): array
    {
        return array_keys(static::$aliases, $id);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    protected function removeService(string $id): void
    {
        if ($this->hasService($id)) {
            unset(
                $this->services[$id],
                $this->frozenServices[$id],
                $this->serviceIdentifier[$id]
            );
        }
    }

    /**
     * @param string $id
     *
     * @return void
     */
    protected function removeGlobalService(string $id): void
    {
        if ($this->hasGlobalService($id)) {
            unset(
                static::$globalServices[$id],
                static::$globalFrozenServices[$id],
                static::$globalServiceIdentifier[$id]
            );
        }
    }

    /**
     * @deprecated Do not use this method anymore. All services are shared by default now.
     *
     * @param \Closure|object|mixed $service
     *
     * @return \Closure|object|mixed
     */
    public function share($service)
    {
        if (method_exists($service, '__invoke')) {
            $serviceHash = spl_object_hash($service);

            $this->sharedServiceHashes[$serviceHash] = true;
        }

        return $service;
    }

    /**
     * @param \Closure|object $service
     *
     * @throws \Spryker\Service\Container\Exception\ContainerException
     *
     * @return \Closure|object
     */
    public function protect($service)
    {
        if (!method_exists($service, '__invoke')) {
            throw new ContainerException('The passed service is not a closure and is not invokable.');
        }

        $this->protectedServices->attach($service);

        return $service;
    }

    /**
     * @param \Closure|object $service
     *
     * @throws \Spryker\Service\Container\Exception\ContainerException
     *
     * @return \Closure|object
     */
    public function factory($service)
    {
        if (!method_exists($service, '__invoke')) {
            throw new ContainerException('The passed service is not a closure and is not invokable.');
        }

        $this->factoryServices->attach($service);

        return $service;
    }

    /**
     * @deprecated Please use `Spryker\Service\Container\ContainerInterface::has()` instead.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        $this->triggerError(sprintf('ArrayAccess the container in Spryker (e.g. isset($container[\'%s\'])) is no longer supported! Please use "ContainerInterface:has()" instead.', $offset));

        return $this->has($offset);
    }

    /**
     * @deprecated Please use `Spryker\Service\Container\ContainerInterface::get()` instead.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->triggerError(sprintf('ArrayAccess the container in Spryker (e.g. $foo = $container[\'%s\']) is no longer supported! Please use "ContainerInterface:get()" instead.', $offset));

        return $this->get($offset);
    }

    /**
     * @deprecated Please use `Spryker\Service\Container\ContainerInterface::set()` instead.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->triggerError(sprintf('ArrayAccess the container in Spryker (e.g. $container[\'%s\'] = $foo) is no longer supported! Please use "ContainerInterface:set()" instead.', $offset));

        // When extend is called for a service which is not registered so far, we store the extension and wait for the service to be added.
        // For BC reasons code like `$container['service'] = $container->extend('service', callable)` is valid and still needs to be supported
        // and we need to make sure that the returned to be extended service is added now.
        if (($this->currentExtendingHash !== null && is_object($value)) && spl_object_hash($value) === $this->currentExtendingHash) {
            $this->currentExtendingHash = null;

            return;
        }

        if (method_exists($value, '__invoke') && !isset($this->sharedServiceHashes[spl_object_hash($value)])) {
            $value = $this->factory($value);
        }

        $this->set($offset, $value);
    }

    /**
     * @deprecated Please use `Spryker\Service\Container\ContainerInterface::remove()` instead.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->triggerError(sprintf('ArrayAccess the container in Spryker (e.g. unset($container[\'%s\'])) is no longer supported! Please use "ContainerInterface:remove()" instead.', $offset));

        $this->remove($offset);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function triggerError(string $message): void
    {
        if ($this->isTriggerErrorEnabled()) {
            // phpcs:ignore
            @trigger_error($message, E_USER_DEPRECATED);
        }
    }

    /**
     * @return bool
     */
    protected function isTriggerErrorEnabled(): bool
    {
        if ($this->isTriggerErrorEnabled === null) {
            $this->isTriggerErrorEnabled = ($this->has(static::TRIGGER_ERROR)
                ? $this->get(static::TRIGGER_ERROR)
                : false);
        }

        return $this->isTriggerErrorEnabled;
    }

    /**
     * This method (currently) exists only for BC reasons.
     *
     * @param string $id
     * @param \Closure $service
     *
     * @return void
     */
    protected function extendLater(string $id, $service): void
    {
        if (!isset($this->toBeExtended[$id])) {
            $this->toBeExtended[$id] = [];
        }

        $this->toBeExtended[$id][] = $service;
    }

    /**
     * @param string $id
     * @param \Closure $service
     *
     * @return void
     */
    protected function extendService(string $id, $service): void
    {
        if (isset($this->toBeExtended[$id])) {
            $this->currentlyExtending = $id;

            foreach ($this->toBeExtended[$id] as $service) {
                $service = $this->extend($id, $service);
            }

            unset($this->toBeExtended[$id]);
            $this->currentlyExtending = null;

            $this->set($id, $service);
        }
    }
}
