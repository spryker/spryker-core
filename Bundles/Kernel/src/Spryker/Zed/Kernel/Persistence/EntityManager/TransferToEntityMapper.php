<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use ArrayObject;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;

class TransferToEntityMapper implements TransferToEntityMapperInterface
{
    const PROPEL_SETTER_PREFIX = 'add';

    /**
     * @var array
     */
    protected static $setterCache = [];

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function mapEntityCollection(EntityTransferInterface $entityTransfer)
    {
        $parentEntity = $this->mapEntity($entityTransfer);

        $transferArray = $entityTransfer->modifiedToArray(false);
        foreach ($transferArray as $propertyName => $value) {
            if (!$value instanceof EntityTransferInterface && !$value instanceof ArrayObject) {
                continue;
            }

            $parentEntitySetterMethodName = $this->findParentEntitySetterMethodName($propertyName, $parentEntity);
            if (is_array($value) || $value instanceof ArrayObject) {
                foreach ($value as $childTransfer) {
                    $entity = $this->mapEntityCollection($childTransfer);
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
     * @param string $transferClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function mapTransferCollection($transferClassName, ActiveRecordInterface $parentEntity)
    {
        $transfer = new $transferClassName;
        $transfer->fromArray($parentEntity->toArray(TableMap::TYPE_FIELDNAME, true, [], true), true);

        return $transfer;
    }

    /**
     * @param string $relationName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return null|string
     */
    protected function findParentEntitySetterMethodName($relationName, ActiveRecordInterface $parentEntity)
    {
        $relationName = $this->toCamelCase($relationName);

        if (isset(static::$setterCache[$relationName])) {
            return static::$setterCache[$relationName];
        }

        $tableMap = $this->getTableMap($parentEntity);
        foreach ($tableMap->getRelations() as $relationMap) {
            if ($relationMap->getPluralName() !== $relationName && $relationMap->getName() !== $relationName) {
                continue;
            }

            static::$setterCache[$relationName] = static::PROPEL_SETTER_PREFIX . $relationMap->getName();

            return static::$setterCache[$relationName];
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
        $entityName = $entityTransfer::$entityNamespace;
        $entity = new $entityName;
        $childTransferArray = $entityTransfer->modifiedToArray(false);
        $entity->fromArray($childTransferArray);

        if ($entity->getPrimaryKey()) {
            $entity->setNew(false);
        }

        return $entity;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentEntity
     *
     * @return \Propel\Runtime\Map\TableMap
     */
    protected function getTableMap(ActiveRecordInterface $parentEntity)
    {
        $tableNameClass = $parentEntity::TABLE_MAP;
        return $tableNameClass::getTableMap();
    }

    /**
     * @param string $relationName
     *
     * @return string
     */
    protected function toCamelCase($relationName)
    {
        return str_replace('_', '', ucwords($relationName, '_'));
    }
}
