<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\ContainerGlobals;
use Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;

trait BundleDependencyProviderResolverAwareTrait
{

    /**
     * @var \Spryker\Zed\Kernel\Container $container
     */
    private $container;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if ($this->container === null) {
            $this->container = $this->createContainerWithProvidedDependencies();
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
    }

    /**
     * @deprecated Use `createContainerWithProvidedDependencies()` instead
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies()
    {
        return $this->createContainerWithProvidedDependencies();
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function createContainerWithProvidedDependencies()
    {
        $container = $this->createContainer();
        $dependencyInjectorCollection = $this->resolveDependencyInjectorCollection();
        $dependencyInjector = $this->createDependencyInjector($dependencyInjectorCollection);
        $dependencyProvider = $this->resolveDependencyProvider();

        $this->provideExternalDependencies($dependencyProvider, $container);
        $this->injectExternalDependencies($dependencyInjector, $container);

        return $container;
    }

    /**
     * @deprecated Use `createDependencyInjector()` instead
     *
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     *
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector
     */
    protected function getDependencyInjector(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        return $this->createDependencyInjector($dependencyInjectorCollection);
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     *
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector
     */
    protected function createDependencyInjector(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        return new DependencyInjector($dependencyInjectorCollection);
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->createDependencyProviderResolver()->resolve($this);
    }

    /**
     * @deprecated Use `createDependencyProviderResolver()` instead
     *
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
    {
        return $this->createDependencyProviderResolver();
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function createDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    abstract protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    );

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    abstract protected function injectExternalDependencies(
        DependencyInjector $dependencyInjector,
        Container $container
    );

    /**
     * @deprecated Use `createContainer()` instead
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        return $this->createContainer();
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function createContainer()
    {
        $containerGlobals = $this->createContainerGlobals();
        $container = new Container($containerGlobals->getContainerGlobals());

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\ContainerGlobals
     */
    protected function createContainerGlobals()
    {
        return new ContainerGlobals();
    }

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    protected function resolveDependencyInjectorCollection()
    {
        return $this->createDependencyInjectorResolver()->resolve($this);
    }

    /**
     * @deprecated Use `createDependencyInjectorResolver()` instead
     *
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function getDependencyInjectorResolver()
    {
        return $this->createDependencyInjectorResolver();
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function createDependencyInjectorResolver()
    {
        return new DependencyInjectorResolver();
    }

}
