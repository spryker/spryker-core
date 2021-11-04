<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Spryker\Zed\AclEntity\Persistence\Exception\AclEntityException;

class ForeignKeyRelationResolverStrategy extends AbstractRelationResolverStrategy
{
    /**
     * @var string
     */
    protected const RELATION_GETTER_TEMPLATE = 'get%s';

    /**
     * @var string
     */
    protected const ENTITY_PREFIX_DEFAULT = 'Spy';

    /**
     * @var string
     */
    protected const RELATION_NOT_FOUND_MESSAGE_TEMPLATE = 'Failed to find relation "%s" for "%s"';

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ObjectCollection {
        $query = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        $parentRelationMap = $this->getParentRelationMap($entity, $aclEntityMetadataTransfer);
        if ($parentRelationMap->getType() === RelationMap::ONE_TO_MANY) {
            /** @var \Propel\Runtime\Map\ColumnMap $columnMap */
            $columnMap = current($parentRelationMap->getRightColumns());
            $foreignKeyColumnName = $columnMap->getPhpName();
            $query->filterBy($foreignKeyColumnName, $entity->getPrimaryKey());

            return $query->find();
        }

        /** @var \Propel\Runtime\Map\ColumnMap $columnMap */
        $columnMap = current($parentRelationMap->getLocalColumns());
        $primaryKeyColumnName = $columnMap->getPhpName();
        $parentPrimaryKey = $entity->getByName($primaryKeyColumnName, TableMap::TYPE_PHPNAME);

        $query->filterByPrimaryKey($parentPrimaryKey);

        return $query->find();
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria {
        $relation = sprintf(
            static::RELATION_TEMPLATE,
            $this->getLeftTableRelationName($query, $aclEntityMetadataTransfer),
            $this->getRightTableRelationName($aclEntityMetadataTransfer),
        );

        return $query->join($relation);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getLeftTableRelationName(ModelCriteria $query, AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $shortClassName = $this->convertFullToShortClassName($aclEntityMetadataTransfer->getEntityNameOrFail());
        if ($shortClassName === $query->getModelShortName() || $query->hasJoin($shortClassName)) {
            return $shortClassName;
        }

        foreach ($query->getJoins() as $name => $join) {
            /** @var string $rightTableName */
            $rightTableName = $join->getRightTableName();
            $joinModel = Propel::getServiceContainer()
                ->getDatabaseMap()
                ->getTable($rightTableName)
                ->getPhpName();
            if ($joinModel === $shortClassName) {
                return $name;
            }
        }

        return $shortClassName;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getRightTableRelationName(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $relationName = $this->convertFullToShortClassName(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
        );
        $targetTableMap = PropelQuery::from($aclEntityMetadataTransfer->getEntityNameOrFail())->getTableMap();
        if ($targetTableMap->hasRelation($relationName)) {
            return $relationName;
        }

        foreach ($targetTableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $relationName) {
                return $relationMap->getName();
            }
        }

        return $relationName;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\AclEntityException
     *
     * @return \Propel\Runtime\Map\RelationMap
     */
    protected function getParentRelationMap(ActiveRecordInterface $entity, AclEntityMetadataTransfer $aclEntityMetadataTransfer): RelationMap
    {
        $entityTableMap = PropelQuery::from(get_class($entity))->getTableMap();
        $parentShortClass = $this->convertFullToShortClassName($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        foreach ($entityTableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $parentShortClass) {
                return $relationMap;
            }
        }

        throw new AclEntityException(
            sprintf(static::RELATION_NOT_FOUND_MESSAGE_TEMPLATE, $parentShortClass, get_class($entity)),
        );
    }
}
