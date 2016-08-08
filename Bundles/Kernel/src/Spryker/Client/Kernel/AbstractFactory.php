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
            $this->container = $this->getContainerWithProvidedDependencies();
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
    }

    /**
     * @return \Spryker\Client\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies()
    {
        $container = $this->getContainer();
        $dependencyProvider = $this->resolveDependencyProvider();
        $this->provideExternalDependencies($dependencyProvider, $container);

        return $container;
    }

    /**
     * @return \Spryker\Client\Kernel\Container
     */
    protected function getContainer()
    {
        $containerGlobals = $this->getContainerGlobals();
        $container = new Container($containerGlobals->getContainerGlobals());

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\ContainerGlobals
     */
    protected function getContainerGlobals()
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
     * @return \Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
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
     * @return \Spryker\Client\Session\SessionClient
     *
     * @deprecated Use getSessionClient() instead.
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
     * @return \Spryker\Client\ZedRequest\ZedRequestClient
     *
     * @deprecated Use getZedRequestClient() instead.
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
     * @return \Spryker\Client\Storage\StorageClient
     *
     * @deprecated Use getStorageClient() instead.
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
     * @return \Spryker\Client\Search\SearchClient
     *
     * @deprecated This method will be removed.
     */
    protected function createSearchClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SEARCH);
    }

}
