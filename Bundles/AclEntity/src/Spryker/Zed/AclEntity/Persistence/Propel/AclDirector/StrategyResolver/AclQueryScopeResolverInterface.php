<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface;

interface AclQueryScopeResolverInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    public function resolve(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryScopeInterface;
}
