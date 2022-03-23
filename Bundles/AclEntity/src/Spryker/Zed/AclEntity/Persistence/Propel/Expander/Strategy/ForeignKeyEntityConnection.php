<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Map\ColumnMap;

class ForeignKeyEntityConnection extends AbstractAclEntityConnection implements AclEntityConnectionInterface
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
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function generateAclEntityJoin(ModelCriteria $query, AclEntityMetadataTransfer $aclEntityMetadataTransfer, string $joinType): Join
    {
        $relationMap = $this->getRelationMap($aclEntityMetadataTransfer);
        $callable = function (ColumnMap $columnMap): string {
            return $columnMap->getFullyQualifiedName();
        };
        $join = new ModelJoin(
            array_map($callable, $relationMap->getLeftColumns()),
            array_map($callable, $relationMap->getRightColumns()),
            $joinType,
        );

        $join->setTableMap($relationMap->getRightTableOrFail());

        return $this->updateJoinAliases($query, $join);
    }
}
