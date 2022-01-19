<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;

class ReferenceColumnEntityRelation extends AbstractAclEntityRelation implements AclEntityRelationInterface
{
    /**
     * @var string
     */
    protected const COLUMN_GETTER_TEMPLATE = 'get%s';

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return bool
     */
    public function isSupported(AclEntityMetadataTransfer $aclEntityMetadataTransfer): bool
    {
        $parentConnectionMetadataTransfer = $aclEntityMetadataTransfer->getParentOrFail()->getConnection();

        return $parentConnectionMetadataTransfer
            && $parentConnectionMetadataTransfer->getReference()
            && $parentConnectionMetadataTransfer->getReferencedColumn()
            && !$this->hasPivotTableConfiguration($parentConnectionMetadataTransfer);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Propel\Runtime\Collection\Collection|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): Collection {
        $entityRelations = new Collection();
        $relationEntityClass = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();
        if ($entity->isNew()) {
            $entityRelations->push(new $relationEntityClass());

            return $entityRelations;
        }

        $query = $this->getQueryByEntityClass($relationEntityClass);
        $referencedColumn = $this->getColumnPhpName(
            $relationEntityClass,
            $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferencedColumnOrFail(),
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
     * @param string $entityClass
     * @param string $columnName
     *
     * @return string
     */
    protected function getColumnGetter(string $entityClass, string $columnName): string
    {
        $columnName = $this->getColumnPhpName($entityClass, $columnName);

        return sprintf(static::COLUMN_GETTER_TEMPLATE, $columnName);
    }

    /**
     * @param string $entityClass
     * @param string $columnName
     *
     * @return string
     */
    protected function getColumnPhpName(string $entityClass, string $columnName): string
    {
        return $this->propelServiceContainer
            ->getDatabaseMap()
            ->getTableByPhpName($entityClass)
            ->getColumn($columnName)
            ->getPhpName();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer $parentConnectionMetadataTransfer
     *
     * @return bool
     */
    protected function hasPivotTableConfiguration(
        AclEntityParentConnectionMetadataTransfer $parentConnectionMetadataTransfer
    ): bool {
        return $parentConnectionMetadataTransfer->getPivotEntityName() !== null;
    }
}
