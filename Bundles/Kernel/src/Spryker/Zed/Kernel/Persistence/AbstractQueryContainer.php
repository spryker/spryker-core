<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

abstract class AbstractQueryContainer implements QueryContainerInterface
{

    const PROPEL_CONNECTION = 'propel connection';

    /**
     * @var PersistenceFactoryInterface
     */
    private $persistenceFactory;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param string $key
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
     * @return AbstractBundleDependencyProvider
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
     * @param AbstractBundleDependencyProvider $dependencyProvider
     * @param Container $container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->providePersistenceLayerDependencies($container);
    }

    /**
     * @return AbstractPersistenceFactory
     */
    protected function getFactory()
    {
        if ($this->persistenceFactory === null) {
            $this->persistenceFactory = $this->resolvePersistenceFactory();
        }

        if ($this->container !== null) {
            $this->persistenceFactory->setContainer($this->container);
        }

        return $this->persistenceFactory;
    }

    /**
     * @throws \Exception
     *
     * @return AbstractPersistenceFactory
     */
    protected function resolvePersistenceFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    protected function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->getProvidedDependency(self::PROPEL_CONNECTION);
    }

}
