<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

interface UserResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function mapUserCollectionToUserResourceCollection(
        UserCollectionTransfer $userCollectionTransfer,
        UserResourceCollectionTransfer $userResourceCollectionTransfer
    ): UserResourceCollectionTransfer;
}
