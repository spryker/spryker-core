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

class PivotTableRelationResolverStrategy extends AbstractRelationResolverStrategy
{
    /**
     * @var string
     */
    protected const PIVOT_TABLE_GETTER_TEMPLATE = 'get%ss';
    /**
     * @var string
     */
    protected const REFERENCE_TABLE_GETTER_TEMPLATE = 'get%s';
    /**
     * @var string
     */
    protected const PIVOT_TABLE_JOINER_TEMPLATE = 'join%ss';
    /**
     * @var string
     */
    protected const REFERENCE_TABLE_JOINER_TEMPLATE = 'join%s';

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
        if ($entity->isNew()) {
            $relations = new ObjectCollection();
            $parentEntity = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();
            $relations->append(new $parentEntity());

            return $relations;
        }

        $targetEntityQuery = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        $pivotEntity = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityNameOrFail();
        $pivotTableMap = PropelQuery::from($pivotEntity)->getTableMap();

        $referenceColumn = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferenceOrFail();

        return $targetEntityQuery
            ->join($this->convertFullToShortClassName($pivotEntity))
            ->addJoinCondition(
                $this->convertFullToShortClassName($pivotEntity),
                $pivotTableMap->getColumn($referenceColumn)->getFullyQualifiedName() . '=?',
                $entity->getPrimaryKey()
            )
            ->find();
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
        $query = $this->addJoinToPivotTable($query, $aclEntityMetadataTransfer);

        return $this->addJoinToTargetTable($query, $aclEntityMetadataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getPivotTableGetter(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $pivotEntity = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityNameOrFail();
        $pivotEntityShortName = basename(str_replace('\\', '/', $pivotEntity));

        return sprintf(static::PIVOT_TABLE_GETTER_TEMPLATE, $pivotEntityShortName);
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
    protected function addJoinToPivotTable(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria {
        $relation = sprintf(
            static::RELATION_TEMPLATE,
            $this->convertFullToShortClassName($aclEntityMetadataTransfer->getEntityNameOrFail()),
            $this->getPivotTableRelationName($aclEntityMetadataTransfer)
        );

        return $query->join($relation);
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
    protected function addJoinToTargetTable(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria {
        $relationName = sprintf(
            static::RELATION_TEMPLATE,
            $this->getPivotTableRelationName($aclEntityMetadataTransfer),
            $this->getTargetTableRelationName($aclEntityMetadataTransfer),
        );

        return $query->join($relationName);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getReferenceTableGetter(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $referencedEntity = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();
        $referenceTableShortName = basename(str_replace('\\', '/', $referencedEntity));

        return sprintf(static::REFERENCE_TABLE_GETTER_TEMPLATE, $referenceTableShortName);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getPivotTableJoiner(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $pivotEntityClass = $this->convertFullToShortClassName(
            $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityNameOrFail()
        );

        return sprintf(static::PIVOT_TABLE_JOINER_TEMPLATE, $pivotEntityClass);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getReferenceTableJoiner(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $referenceEntityClass = $this->convertFullToShortClassName(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail()
        );

        return sprintf(static::REFERENCE_TABLE_JOINER_TEMPLATE, $referenceEntityClass);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getPivotTableRelationName(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $pivotTableEntity = $aclEntityMetadataTransfer
            ->getParentOrFail()
            ->getConnectionOrFail()
            ->getPivotEntityNameOrFail();
        $relationName = $this->convertFullToShortClassName($pivotTableEntity);

        $tableMap = PropelQuery::from($aclEntityMetadataTransfer->getEntityNameOrFail())->getTableMap();
        if ($tableMap->hasRelation($relationName)) {
            return $relationName;
        }

        foreach ($tableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $relationName) {
                return $relationMap->getName();
            }
        }

        return $relationName;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getTargetTableRelationName(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $targetTableEntity = $aclEntityMetadataTransfer
            ->getParentOrFail()
            ->getEntityNameOrFail();
        $relationName = $this->convertFullToShortClassName($targetTableEntity);

        $pivotQuery = PropelQuery::from(
            $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityNameOrFail()
        );
        $pivotTableMap = $pivotQuery->getTableMap();
        if ($pivotTableMap->hasRelation($relationName)) {
            return $relationName;
        }
        foreach ($pivotTableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $relationName) {
                return $relationMap->getName();
            }
        }

        return $relationName;
    }
}
