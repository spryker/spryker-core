<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiUsersAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Generated\Shared\Transfer\UsersRestAttributesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\UsersBackendApi\UsersBackendApiConfig;

class UserResourceMapper implements UserResourceMapperInterface
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
    ): UserResourceCollectionTransfer {
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            $userResourceTransfer = $this->mapUserTransferToUserResourceTransfer($userTransfer, new GlueResourceTransfer());

            $userResourceCollectionTransfer->addUserResource($userResourceTransfer);
        }

        return $userResourceCollectionTransfer;
    }

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
    ): UsersRestAttributesTransfer {
        return $usersRestAttributesTransfer->fromArray($apiUsersAttributesTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $userResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapUserTransferToUserResourceTransfer(
        UserTransfer $userTransfer,
        GlueResourceTransfer $userResourceTransfer
    ): GlueResourceTransfer {
        $apiUsersAttributesTransfer = $this->mapUserTransferToApiUsersAttributesTransfer($userTransfer, new ApiUsersAttributesTransfer());

        return $userResourceTransfer
            ->setId($userTransfer->getUuidOrFail())
            ->setType(UsersBackendApiConfig::RESOURCE_TYPE_USERS)
            ->setAttributes($apiUsersAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\ApiUsersAttributesTransfer $apiUsersAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiUsersAttributesTransfer
     */
    protected function mapUserTransferToApiUsersAttributesTransfer(
        UserTransfer $userTransfer,
        ApiUsersAttributesTransfer $apiUsersAttributesTransfer
    ): ApiUsersAttributesTransfer {
        return $apiUsersAttributesTransfer->fromArray($userTransfer->toArray(), true);
    }
}
