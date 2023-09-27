<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendApi\Dependency\Resource;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

interface PickingListsUsersBackendApiToUsersBackendApiResourceInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUsersResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;
}
