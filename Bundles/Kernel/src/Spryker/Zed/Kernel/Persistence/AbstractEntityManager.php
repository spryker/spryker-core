<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransferToEntityMapper;

abstract class AbstractEntityManager implements EntityManagerInterface
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
     * @return \Spryker\Zed\Kernel\Container $container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->providePersistenceLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
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
        /** @var \Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface $factory */
        $factory = $this->getFactoryResolver()->resolve($this);

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * This method saves EntityTransferInterface data objects, it will try to persist whole object tree in single transaction.
     *
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function save(EntityTransferInterface $entityTransfer)
    {
        $transferToEntityMapper = $this->createTransferToEntityMapper();
        $parentEntity = $transferToEntityMapper->mapEntityCollection($entityTransfer);
        $parentEntity->save();

        return $transferToEntityMapper->mapTransferCollection(get_class($entityTransfer), $parentEntity);
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\EntityManager\TransferToEntityMapper
     */
    protected function createTransferToEntityMapper()
    {
        return new TransferToEntityMapper();
    }
}
