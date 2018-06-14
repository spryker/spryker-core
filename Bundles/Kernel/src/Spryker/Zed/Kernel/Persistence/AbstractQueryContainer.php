<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

abstract class AbstractQueryContainer implements QueryContainerInterface
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    private $factory;

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->providePersistenceLayerDependencies($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectExternalDependencies(
        DependencyInjector $dependencyInjector,
        Container $container
    ) {
        return $dependencyInjector->injectPersistenceLayerDependencies($container);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractPersistenceFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface
     */
    private function resolveFactory()
    {
        /** @var \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface $class */
        $class = $this->getFactoryResolver()->resolve($this);

        return $class;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @api
     *
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getConnection()
    {
        return Propel::getConnection();
    }
}
