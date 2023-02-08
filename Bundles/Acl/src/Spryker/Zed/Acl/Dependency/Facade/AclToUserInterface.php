<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Dependency\Facade;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AclToUserInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user): bool;

    /**
     * @return bool
     */
    public function hasCurrentUser(): bool;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer;
}
