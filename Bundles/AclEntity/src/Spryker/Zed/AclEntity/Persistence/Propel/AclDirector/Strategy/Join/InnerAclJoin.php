<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class InnerAclJoin extends AbstractAclJoin
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    public function isSupported(Join $join): bool
    {
        return !$join->getJoinType()
            || $join->getJoinType() === Criteria::INNER_JOIN
            || $join->getJoinType() === Criteria::JOIN;
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
        if ($this->isAclEntitySegmentTableJoin($join) || $this->isPivotTableJoin($join)) {
            return $query;
        }
        $joinClass = $this->getModelClass($join->getRightTableName() ?: '');
        $relationQuery = $this->getQuery($joinClass);
        if ($this->isSubEntity($joinClass)) {
            $query = $this->joinSubEntityRoot($query, $joinClass);
            $rootAclEntityMetadataTransfer = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass($joinClass);
            $relationQuery = $this->getQuery($rootAclEntityMetadataTransfer->getEntityNameOrFail());
        }

        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $relationQuery,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ,
        );

        return $this->aclEntityQueryMerger->mergeQueries(
            $query,
            $aclQueryScope->applyAclRuleOnSelectQuery($relationQuery, $aclEntityRuleCollectionTransfer),
        );
    }
}
