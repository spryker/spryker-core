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

class LeftAclJoin extends AbstractAclJoin
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    public function isSupported(Join $join): bool
    {
        return $join->getJoinType() === Criteria::LEFT_JOIN;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
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
        $relationQuery = $this->updateJoinTypes($relationQuery, Criteria::LEFT_JOIN);
        $query = $this->aclEntityQueryMerger->mergeQueries($query, $relationQuery);

        return $this->hasSegmentJoin($relationQuery) ? $this->extendQueryWithSegmentConditions($query, $join) : $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function extendQueryWithSegmentConditions(ModelCriteria $query, Join $join): ModelCriteria
    {
        $aclEntitySegmentJoin = $this->getAclEntitySegmentJoin($query);

        $aclEntitySegmentPrimaryKeyColumn = $this->getPrimaryKeyColumn($aclEntitySegmentJoin->getRightTableName() ?: '');
        $joinPrimaryKeyColumn = $this->getPrimaryKeyColumn($join->getRightTableName() ?: '');

        /** @var literal-string $where */
        $where = sprintf(
            '(%s IS NOT NULL OR %s IS NULL)',
            $aclEntitySegmentPrimaryKeyColumn,
            $joinPrimaryKeyColumn,
        );
        $query->where($where);

        return $query;
    }
}
