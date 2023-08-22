<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiUsersAttributesTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Generated\Shared\Transfer\UsersRestAttributesTransfer;

interface UserResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function mapUserCollectionToUsersResourceCollection(
        UserCollectionTransfer $userCollectionTransfer,
        UserResourceCollectionTransfer $userResourceCollectionTransfer
    ): UserResourceCollectionTransfer;

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface::mapUserCollectionToUsersResourceCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function mapUserCollectionToUserResourceCollection(
        UserCollectionTransfer $userCollectionTransfer,
        UserResourceCollectionTransfer $userResourceCollectionTransfer
    ): UserResourceCollectionTransfer;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ApiUsersAttributesTransfer $apiUsersAttributesTransfer
     * @param \Generated\Shared\Transfer\UsersRestAttributesTransfer $usersRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\UsersRestAttributesTransfer
     */
    public function mapApiUsersAttributesTransferToUsersRestAttributesTransfer(
        ApiUsersAttributesTransfer $apiUsersAttributesTransfer,
        UsersRestAttributesTransfer $usersRestAttributesTransfer
    ): UsersRestAttributesTransfer;
}
