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
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;

class ForeignKeyEntityRelation extends AbstractAclEntityRelation implements AclEntityRelationInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return bool
     */
    public function isSupported(AclEntityMetadataTransfer $aclEntityMetadataTransfer): bool
    {
        return !$aclEntityMetadataTransfer->getParentOrFail()->getConnection();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\Collection\Collection|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    public function getRelations(
        ActiveRecordInterface $entity,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): Collection {
        $query = PropelQuery::from($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail());
        $parentRelationMap = $this->getRelationMap($aclEntityMetadataTransfer);
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

        return $query->filterByPrimaryKey($parentPrimaryKey)->find();
    }
}
