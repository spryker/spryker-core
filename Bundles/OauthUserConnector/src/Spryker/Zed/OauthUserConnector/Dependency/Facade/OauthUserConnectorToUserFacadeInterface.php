<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Dependency\Facade;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;

interface OauthUserConnectorToUserFacadeInterface
{
    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username): bool;

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash): bool;

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer;
}
