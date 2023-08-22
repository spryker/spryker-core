<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

class PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceBridge implements PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface
     */
    protected $usersBackendApiResource;

    /**
     * @param \Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface $usersBackendApiResource
     */
    public function __construct($usersBackendApiResource)
    {
        $this->usersBackendApiResource = $usersBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUsersResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        return $this->usersBackendApiResource->getUsersResources($userCriteriaTransfer);
    }
}
