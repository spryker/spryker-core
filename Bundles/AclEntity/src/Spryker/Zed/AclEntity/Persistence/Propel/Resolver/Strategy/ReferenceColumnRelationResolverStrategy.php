<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Resolver\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;

class ReferenceColumnRelationResolverStrategy extends AbstractRelationResolverStrategy
{
    protected const COLUMN_GETTER_TEMPLATE = 'get%s';
    protected const JOIN_COLUMN_TEMPLATE = '%s.%s';

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ObjectCollection {
        $entityRelations = new ObjectCollection();
        $relationEntityClass = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();
        if ($entity->isNew()) {
            $entityRelations->push(new $relationEntityClass());

            return $entityRelations;
        }

        $query = $this->getQueryByEntityClass($relationEntityClass);
        $referencedColumn = $this->getColumnPhpName(
            $relationEntityClass,
            $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferencedColumnOrFail()
        );
        $referenceColumn = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferenceOrFail();

        $referenceGetter = $this->getColumnGetter(get_class($entity), $referenceColumn);

        $callable = [$entity, $referenceGetter];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
        }

        $query->filterBy($referencedColumn, call_user_func($callable));

        foreach ($query->find() as $entityRelation) {
            $entityRelations->push($entityRelation);
        }

        return $entityRelations;
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
        $tableMap = $this->getTableMapByEntityClass($aclEntityMetadataTransfer->getEntityNameOrFail());
        $referencedTableMap = $this->getTableMapByEntityClass(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail()
        );
        $join = new ModelJoin(
            sprintf(
                static::JOIN_COLUMN_TEMPLATE,
                $tableMap->getName(),
                $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferenceOrFail()
            ),
            sprintf(
                static::JOIN_COLUMN_TEMPLATE,
                $referencedTableMap->getName(),
                $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferencedColumnOrFail()
            )
        );
        $joinTableMap = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail())
            ->getTableMap();
        $join->setTableMap($joinTableMap);
        $query->addJoinObject(
            $join,
            $this->convertFullToShortClassName($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail())
        );

        return $query;
    }

    /**
     * @param string $entityClass
     * @param string $columnName
     *
     * @return string
     */
    protected function getColumnGetter(string $entityClass, string $columnName): string
    {
        $columnName = $this->getColumnPhpName($entityClass, $columnName);

        return sprintf(self::COLUMN_GETTER_TEMPLATE, $columnName);
    }

    /**
     * @param string $entityClass
     * @param string $columnName
     *
     * @return string
     */
    protected function getColumnPhpName(string $entityClass, string $columnName): string
    {
        return Propel::getServiceContainer()
            ->getDatabaseMap()
            ->getTableByPhpName($entityClass)
            ->getColumn($columnName)
            ->getPhpName();
    }
}
