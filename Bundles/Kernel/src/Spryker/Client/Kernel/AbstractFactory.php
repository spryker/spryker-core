<?php

namespace Spryker\Client\Kernel;

use Generated\Client\Ide\AutoCompletion;
use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Client\Kernel\FactoryInterface;
use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\ZedRequest\ZedRequestClient;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Search\SearchClient;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

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
     * @throws ContainerKeyNotFoundException
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
     * @throws DependencyProviderNotFoundException
     *
     * @return AbstractDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->getDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return DependencyProviderResolver
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
     * @return SessionClient
     */
    protected function createSessionClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return ZedRequestClient
     */
    protected function createZedRequestClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return StorageClient
     */
    protected function createStorageClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @return SearchClient
     */
    protected function createSearchClient()
    {
        return $this->getProvidedDependency(AbstractDependencyProvider::CLIENT_SEARCH);
    }

}
