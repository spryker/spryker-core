<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Generated\Shared\Transfer\GroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\GroupTransfer;

interface AclRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer): ?GroupTransfer;
}
