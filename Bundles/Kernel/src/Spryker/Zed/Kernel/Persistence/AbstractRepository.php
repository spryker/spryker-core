<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Generated\Shared\Transfer\CriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Zed\Kernel\Persistence\Repository\RelationMapper;
use Spryker\Zed\Kernel\Persistence\Repository\TransferObjectFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

abstract class AbstractRepository
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
     * @return \Spryker\Zed\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\CriteriaTransfer|null $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $modelCriteria, CriteriaTransfer $criteriaTransfer = null)
    {
        $criteria = $modelCriteria->setFormatter(TransferObjectFormatter::class);

        if (!$criteriaTransfer) {
            return $criteria;
        }

        if ($criteriaTransfer->getLimit()) {
            $criteria->setLimit($criteriaTransfer->getLimit());
        }

        if ($criteriaTransfer->getOffset()) {
            $criteria->setOffset($criteriaTransfer->getOffset());
        }

        if ($criteriaTransfer->getSortBy() && $criteriaTransfer->getSortOrder()) {
            $criteria->orderBy($criteriaTransfer->getSortBy(), $criteriaTransfer->getSortOrder());
        }

        return $criteria;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[] $collection
     * @param string $relation
     * @param \Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[]
     */
    public function populateCollectionWithRelation(array &$collection, $relation, Criteria $criteria = null)
    {
        return $this->createRelationMapper()->populateCollectionWithRelation($collection, $relation, $criteria);
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\Repository\RelationMapperInterface
     */
    protected function createRelationMapper()
    {
        return (new RelationMapper());
    }
}
