<?php

namespace Spryker\Client\Kernel;

use Generated\Client\Ide\AutoCompletion;
use Spryker\Client\Kernel\DependencyContainer\DependencyContainerInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\ZedRequest\ZedRequestClient;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Search\SearchClient;

abstract class AbstractDependencyContainer implements DependencyContainerInterface
{

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var AutoCompletion|LocatorLocatorInterface
     */
    private $locator;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }

    /**
     * @param Container $container
     *
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        $this->validateContainerExists();
        $this->validateKeyExists($key);

        return $this->container[$key];
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

    /**
     * @throws \ErrorException
     * @return void
     */
    protected function validateContainerExists()
    {
        if ($this->container === null) {
            throw new \ErrorException('Container does not exist in ' . get_class($this));
        }
    }

    /**
     * @param $key
     *
     * @throws \ErrorException
     * @return void
     */
    protected function validateKeyExists($key)
    {
        if ($this->container->offsetExists($key) === false) {
            throw new \ErrorException('Key ' . $key . ' does not exist in container: ' . get_class($this));
        }
    }

}
