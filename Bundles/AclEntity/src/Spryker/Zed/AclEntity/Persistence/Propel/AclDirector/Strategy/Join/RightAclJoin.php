<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\AclEntity\AclEntityConstants;

class RightAclJoin extends AbstractAclJoin
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    public function isSupported(Join $join): bool
    {
        return $join->getJoinType() === Criteria::RIGHT_JOIN;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnSelectQueryRelation(
        ModelCriteria $query,
        Join $join,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ModelCriteria {
        /** @var string $rightTableName */
        $rightTableName = $join->getRightTableName();
        $joinClass = $this->getModelClass($rightTableName);
        $relationQuery = $this->getQuery($joinClass);
        if ($this->isSubEntity($joinClass)) {
            $query = $this->joinSubEntityRoot($query, $joinClass, Criteria::LEFT_JOIN);
            $rootAclEntityMetadataTransfer = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass($joinClass);
            $relationQuery = $this->getQuery($rootAclEntityMetadataTransfer->getEntityNameOrFail());
        }

        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $relationQuery,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ,
        );

        if (!$this->isReadableQuery($aclQueryScope, $relationQuery)) {
            return $this->forbidJoin($query, $join);
        }

        $relationQuery = $aclQueryScope->applyAclRuleOnSelectQuery($relationQuery, $aclEntityRuleCollectionTransfer);
        if ($this->hasSegmentJoin($relationQuery)) {
            $relationQuery = $this->extendQueryWithSegmentConditions($relationQuery);
        }

        return $this->aclEntityQueryMerger->mergeQueries($query, $relationQuery);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function extendQueryWithSegmentConditions(ModelCriteria $query): ModelCriteria
    {
        /** @var \Propel\Runtime\Map\ColumnMap $queryPrimaryKey */
        $queryPrimaryKey = current($this->getPrimaryKeys($query->getTableMap()->getName()));

        $aclEntitySegmentJoin = $this->getAclEntitySegmentJoin($query);

        /** @var string $rightTableName */
        $rightTableName = $aclEntitySegmentJoin->getRightTableName();

        /** @var \Propel\Runtime\Map\ColumnMap $aclEntitySegmentPrimaryKey */
        $aclEntitySegmentPrimaryKey = current($this->getPrimaryKeys($rightTableName));

        $query->where(
            sprintf(
                '%s IS NULL OR %s IS NOT NULL',
                $queryPrimaryKey->getFullyQualifiedName(),
                $aclEntitySegmentPrimaryKey->getFullyQualifiedName(),
            ),
        );

        return $query;
    }
}
