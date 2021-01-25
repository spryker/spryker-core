<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Facade;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MerchantUserToUserFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser);

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);
}
