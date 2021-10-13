<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Sorter;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;

class AclEntityRuleCollectionTransferTransferSorter implements AclEntityRuleCollectionTransferSorterInterface
{
    /**
     * @var array<int>
     */
    protected $scopePriority;

    /**
     * @param array<int> $scopePriority
     */
    public function __construct(array $scopePriority)
    {
        $this->scopePriority = $scopePriority;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function sortByScopePriority(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclEntityRuleCollectionTransfer {
        $aclEntityRuleCollectionTransfer->getAclEntityRules()->uasort(
            function (
                AclEntityRuleTransfer $aclEntityRuleTransfer1,
                AclEntityRuleTransfer $aclEntityRuleTransfer2
            ): int {
                $aclEntityRule1Priority = $this->scopePriority[$aclEntityRuleTransfer1->getScopeOrFail()];
                $aclEntityRule2Priority = $this->scopePriority[$aclEntityRuleTransfer2->getScopeOrFail()];
                if ($aclEntityRule1Priority === $aclEntityRule2Priority) {
                    return 0;
                }

                return ($aclEntityRule1Priority > $aclEntityRule2Priority) ? -1 : 1;
            }
        );

        return $aclEntityRuleCollectionTransfer;
    }
}
