<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;

class ReferenceColumnEntityConnection extends AbstractAclEntityConnection implements AclEntityConnectionInterface
{
    /**
     * @var string
     */
    protected const JOIN_COLUMN_TEMPLATE = '%s.%s';

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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function generateAclEntityJoin(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType
    ): Join {
        $tableMap = $this->getTableMapByEntityClass($aclEntityMetadataTransfer->getEntityNameOrFail());
        $referencedTableMap = $this->getTableMapByEntityClass(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
        );
        $join = new ModelJoin(
            sprintf(
                static::JOIN_COLUMN_TEMPLATE,
                $tableMap->getName(),
                $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferenceOrFail(),
            ),
            sprintf(
                static::JOIN_COLUMN_TEMPLATE,
                $referencedTableMap->getName(),
                $aclEntityMetadataTransfer->getParentOrFail()->getConnectionOrFail()->getReferencedColumnOrFail(),
            ),
            $joinType,
        );
        $join->setTableMap($referencedTableMap);

        return $this->updateJoinAliases($query, $join);
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
