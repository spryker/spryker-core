<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\Repository;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\Collection\Exception\UnsupportedRelationException;
use Propel\Runtime\Map\RelationMap;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;

class RelationMapper implements RelationMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[] $collection
     * @param string $relation
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @throws \Propel\Runtime\Collection\Exception\UnsupportedRelationException
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[]
     */
    public function populateCollectionWithRelation(array &$collection, $relation, ?Criteria $criteria = null)
    {
        if (count($collection) === 0) {
            return $collection;
        }

        $entityTransfer = $collection[0];

        $relationMap = $this->getRelation($entityTransfer, $relation);

        if ($relationMap->getType() !== RelationMap::ONE_TO_MANY) {
            throw new UnsupportedRelationException('Only one to many relations supported');
        }

        $symRelationMap = $relationMap->getSymmetricalRelation();

        $foreignPhpIdName = $this->getForeignPhpIdGetterName($symRelationMap);
        $localPhpIdName = $this->getLocalPhpIdGetterName($symRelationMap);

        $primaryIds = $this->extractPrimaryIds($collection, $foreignPhpIdName);
        $entityQueryClass = $this->createEntityQueryClass($relationMap);

        $relatedObjects = $this->findRelations($entityQueryClass, $symRelationMap, $primaryIds, $criteria);

        $relatedCollection = $this->mapRelations($collection, $relationMap, $relatedObjects, $foreignPhpIdName, $localPhpIdName);

        return $relatedCollection;
    }

    /**
     * @param \Propel\Runtime\Map\RelationMap $symRelationMap
     *
     * @return string
     */
    protected function getForeignPhpIdGetterName(RelationMap $symRelationMap)
    {
        $foreignColumnMap = current($symRelationMap->getForeignColumns());

        return 'get' . $foreignColumnMap->getPhpName();
    }

    /**
     * @param \Propel\Runtime\Map\RelationMap $symRelationMap
     *
     * @return string
     */
    protected function getLocalPhpIdGetterName(RelationMap $symRelationMap)
    {
        $localColumnMap = current($symRelationMap->getLocalColumns());

        return 'get' . $localColumnMap->getPhpName();
    }

    /**
     * @param array $collection
     * @param string $foreignPhpIdName
     *
     * @return array
     */
    protected function extractPrimaryIds(array $collection, $foreignPhpIdName)
    {
        $primaryIds = [];
        foreach ($collection as $entityTransfer) {
            $primaryIds[] = $entityTransfer->$foreignPhpIdName();
        }

        return $primaryIds;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Propel\Runtime\Map\RelationMap $symRelationMap
     * @param array $primaryIds
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface[]
     */
    protected function findRelations(
        ModelCriteria $query,
        RelationMap $symRelationMap,
        array $primaryIds,
        ?Criteria $criteria = null
    ) {
        if ($criteria !== null) {
            $query->mergeWith($criteria);
        }

        $foreignKey = key($symRelationMap->getColumnMappings());

        return $query
            ->addUsingAlias($foreignKey, $primaryIds, Criteria::IN)
            ->setFormatter(TransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Propel\Runtime\Map\TableMap
     */
    protected function getTableMap(EntityTransferInterface $entityTransfer)
    {
        $entityNamespace = $entityTransfer::$entityNamespace;
        $tableNameClass = $entityNamespace::TABLE_MAP;

        return $tableNameClass::getTableMap();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     * @param string $relation
     *
     * @return \Propel\Runtime\Map\RelationMap
     */
    protected function getRelation(EntityTransferInterface $entityTransfer, $relation)
    {
        $tableMap = $this->getTableMap($entityTransfer);

        return $tableMap->getRelation($relation);
    }

    /**
     * @param \Propel\Runtime\Map\RelationMap $relationMap
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createEntityQueryClass(RelationMap $relationMap)
    {
        return PropelQuery::from($relationMap->getRightTable()->getClassName());
    }

    /**
     * @param array $collection
     * @param \Propel\Runtime\Map\RelationMap $relationMap
     * @param array $relatedObjects
     * @param string $foreignPhpIdName
     * @param string $localPhpIdName
     *
     * @return array
     */
    protected function mapRelations(
        array &$collection,
        RelationMap $relationMap,
        array $relatedObjects,
        $foreignPhpIdName,
        $localPhpIdName
    ) {

        $addMethod = 'add' . $relationMap->getPluralName();

        $relatedCollection = [];
        foreach ($relatedObjects as $relatedEntityTransfer) {
            foreach ($collection as $entityTransfer) {
                if ($entityTransfer->$foreignPhpIdName() !== $relatedEntityTransfer->$localPhpIdName()) {
                    continue;
                }
                $relatedCollection[] = $relatedEntityTransfer;
                $entityTransfer->$addMethod($relatedEntityTransfer);
            }
        }

        return $relatedCollection;
    }
}
