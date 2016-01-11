<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use Spryker\Zed\Propel\Communication\Plugin\Connection;

abstract class AbstractQueryContainer implements QueryContainerInterface
{

    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var PersistenceFactoryInterface
     */
    private $factory;

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
     * @param AbstractPersistenceFactory $factory
     *
     * @return self
     */
    public function setFactory(AbstractPersistenceFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return AbstractPersistenceFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws FactoryNotFoundException
     *
     * @return AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return (new Connection())->get();
    }

}
