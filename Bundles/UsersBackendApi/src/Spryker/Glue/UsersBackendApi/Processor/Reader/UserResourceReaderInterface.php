<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

interface UserResourceReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReaderInterface::getUserResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResourceCollection(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;
}
