<?php

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\ZedRequest\ZedRequestClient;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Search\SearchClient;

abstract class AbstractFactory
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param $key
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if ($this->container === null) {
            $dependencyProvider = $this->resolveDependencyProvider();
            $container = new Container();
            $this->provideExternalDependencies($dependencyProvider, $container);
            $this->container = $container;
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
    }

    /**
     * @throws \Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return AbstractDependencyProvider
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
     * @param AbstractDependencyProvider $dependencyProvider
     * @param Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(AbstractDependencyProvider $dependencyProvider, Container $container)
    {
        $dependencyProvider->provideServiceLayerDependencies($container);
    }

    /**
     * @return \Spryker\Client\Session\SessionClient
     */
    protected function createSessionClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected function createZedRequestClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\Storage\StorageClient
     */
    protected function createStorageClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @return \Spryker\Client\Search\SearchClient
     */
    protected function createSearchClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SEARCH);
    }

}
