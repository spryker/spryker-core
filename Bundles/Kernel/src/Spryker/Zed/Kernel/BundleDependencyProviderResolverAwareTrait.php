<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjector;
use Spryker\Zed\Kernel\ClassResolver\DependencyInjectionProvider\DependencyInjectionProviderResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
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
            $this->container = $this->getContainerWithProvidedDependencies();
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies()
    {
        $container = $this->getContainer();
        $dependencyInjector = $this->getDependencyInjector();
        $dependencyProvider = $this->resolveDependencyProvider();

        $this->provideExternalDependencies($dependencyProvider, $container);
        $container = $dependencyInjector->inject($container);

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjector
     */
    protected function getDependencyInjector()
    {
        $dependencyInjectionProviderCollection = $this->resolveDependencyInjectionProvider();
        $dependencyInjector = new DependencyInjector($dependencyInjectionProviderCollection);

        return $dependencyInjector;
    }

    /**
     * @throws \Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->getDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
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
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        return new Container();
    }

    /**
     * @return \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollectionInterface
     */
    protected function resolveDependencyInjectionProvider()
    {
        return $this->getDependencyInjectionProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\DependencyInjectionProvider\DependencyInjectionProviderResolver
     */
    protected function getDependencyInjectionProviderResolver()
    {
        return new DependencyInjectionProviderResolver();
    }

}
