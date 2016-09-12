<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Shared\Kernel\ContainerGlobals;

abstract class AbstractFactory
{

    /**
     * @var \Spryker\Client\Kernel\Container
     */
    private $container;

    /**
     * @param \Spryker\Client\Kernel\Container $container
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
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
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
     * @return \Spryker\Client\Kernel\Container
     */
    protected function createContainerWithProvidedDependencies()
    {
        $container = $this->createContainer();
        $dependencyProvider = $this->resolveDependencyProvider();
        $this->provideExternalDependencies($dependencyProvider, $container);

        return $container;
    }

    /**
     * @deprecated Use `createContainer()` instead
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function getContainer()
    {
        return $this->createContainer();
    }

    /**
     * @return \Spryker\Client\Kernel\Container
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
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->getDependencyProviderResolver()->resolve($this);
    }

    /**
     * @deprecated Use `createDependencyProviderResolver` instead
     *
     * @return \Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
    {
        return $this->createDependencyProviderResolver();
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function createDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractDependencyProvider $dependencyProvider
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(AbstractDependencyProvider $dependencyProvider, Container $container)
    {
        $dependencyProvider->provideServiceLayerDependencies($container);
    }

    /**
     * @deprecated Use getSessionClient() instead.
     *
     * @return \Spryker\Client\Session\SessionClient
     */
    protected function createSessionClient()
    {
        return $this->getSessionClient();
    }

    /**
     * @return \Spryker\Client\Session\SessionClient
     */
    public function getSessionClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @deprecated Use getZedRequestClient() instead.
     *
     * @return \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected function createZedRequestClient()
    {
        return $this->getZedRequestClient();
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClient
     */
    public function getZedRequestClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @deprecated Use getStorageClient() instead.
     *
     * @return \Spryker\Client\Storage\StorageClient
     */
    protected function createStorageClient()
    {
        return $this->getStorageClient();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClient
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @deprecated This method will be removed.
     *
     * @return \Spryker\Client\Search\SearchClient
     */
    protected function createSearchClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SEARCH);
    }

}
