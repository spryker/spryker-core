<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

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
     * @return void
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    )
    {
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
    )
    {
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
     * This method saves SpyNameEntityTransfer data objects, it will try to persist whole object tree in single transaction.
     *
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(EntityTransferInterface $entityTransfer)
    {
        $parentEntity = $this->mapEntityCollection($entityTransfer);
        $parentEntity->save();

        return $this->mapTransferCollection(get_class($entityTransfer), $parentEntity);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function mapEntityCollection(
        EntityTransferInterface $entityTransfer,
        ActiveRecordInterface $parentEntity = null
    ) {
        if ($parentEntity === null) {
            $parentEntity = $this->mapEntity($entityTransfer);
        }

        $transferArray = $entityTransfer->modifiedToArray(false);
        foreach ($transferArray as $propertyName => $value) {
            if (!$value instanceof EntityTransferInterface && !$value instanceof \ArrayObject) {
                continue;
            }

            $parentEntitySetterMethodName = $this->findParentEntitySetterMethodName($propertyName, $parentEntity);
            if (is_array($value) || $value instanceof \ArrayObject) {
                foreach ($value as $childTransfer) {
                    $childEntity = $this->mapEntity($childTransfer);
                    $entity = $this->mapEntityCollection($childTransfer, $childEntity);
                    $parentEntity->$parentEntitySetterMethodName($entity);
                }
                continue;
            }

            $childEntity = $this->mapEntity($value);
            $parentEntity->$parentEntitySetterMethodName($childEntity);
        }

        return $parentEntity;

    }

    /**
     * @param string $relationName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return null|string
     */
    protected function findParentEntitySetterMethodName($relationName, ActiveRecordInterface $parentEntity)
    {
        $relationName = str_replace('_', '', ucwords($relationName, '_'));

        $tableNameClass = $parentEntity::TABLE_MAP;
        $tableMap = $tableNameClass::getTableMap();
        foreach ($tableMap->getRelations() as $relationMap) {
            if ($relationMap->getPluralName() !== $relationName && $relationMap->getName() !== $relationName) {
                continue;
            }

            return 'add' . $relationMap->getName();
        }
        return null;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected function mapEntity(EntityTransferInterface $entityTransfer)
    {
        $entityName = $entityTransfer->_getEntityNamespace();
        $entity = new $entityName;
        $childTransferArray = $entityTransfer->modifiedToArray(false);
        $entity->fromArray($childTransferArray);

        return $entity;
    }

    /**
     * @param string $transferClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function mapTransferCollection($transferClassName, ActiveRecordInterface $parentEntity)
    {
        $transfer = new $transferClassName;
        $transfer->fromArray($parentEntity->toArray(TableMap::TYPE_FIELDNAME, true, [], true), true);

        return $transfer;
    }
}
