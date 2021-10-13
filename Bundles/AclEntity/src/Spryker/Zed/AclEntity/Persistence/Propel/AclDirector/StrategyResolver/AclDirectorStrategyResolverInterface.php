<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface;

interface AclDirectorStrategyResolverInterface
{
    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function resolveByEntity(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryDirectorStrategyInterface;

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $operationMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    public function resolveByQuery(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $operationMask
    ): AclQueryDirectorStrategyInterface;
}
