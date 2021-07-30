<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Sorter;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;

interface AclEntityRuleCollectionTransferSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function sortByScopePriority(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclEntityRuleCollectionTransfer;
}
