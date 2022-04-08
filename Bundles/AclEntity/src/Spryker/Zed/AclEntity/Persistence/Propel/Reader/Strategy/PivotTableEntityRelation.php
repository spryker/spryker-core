<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;

/**
 * @deprecated Use the combination of {@link \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\ForeignKeyEntityRelation}
 * or {@link \Spryker\Zed\AclEntity\Persistence\Propel\Reader\Strategy\ReferenceColumnEntityRelation} instead.
 */
class PivotTableEntityRelation extends AbstractAclEntityRelation implements AclEntityRelationInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return bool
     */
    public function isSupported(AclEntityMetadataTransfer $aclEntityMetadataTransfer): bool
    {
        $aclEntityParentConnectionMetadataTransfer = $aclEntityMetadataTransfer->getParentOrFail()->getConnection();

        return $aclEntityParentConnectionMetadataTransfer && $aclEntityParentConnectionMetadataTransfer->getPivotEntityName();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): Collection {
        trigger_error($this->getDeprecationMessage(), E_USER_DEPRECATED);
        if ($entity->isNew()) {
            $relations = new Collection();
            $parentEntity = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();
            $relations->append(new $parentEntity());

            return $relations;
        }

        $targetEntityQuery = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        $pivotEntity = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getPivotEntityNameOrFail();
        $pivotTableMap = PropelQuery::from($pivotEntity)->getTableMap();

        $referenceColumn = $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferenceOrFail();

        return $targetEntityQuery
            ->join($this->getShortClassName($pivotEntity))
            ->addJoinCondition(
                $this->getShortClassName($pivotEntity),
                $pivotTableMap->getColumn($referenceColumn)->getFullyQualifiedName() . '=?',
                $entity->getPrimaryKey(),
            )
            ->find();
    }

    /**
     * @return string
     */
    protected function getDeprecationMessage(): string
    {
        return sprintf(
            '[Spryker/AclEntity] %s is deprecated. Please configure your AclEntityMetadata by %s.',
            static::class,
            sprintf('%s, %s', ForeignKeyEntityRelation::class, ReferenceColumnEntityRelation::class),
        );
    }
}
