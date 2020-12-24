<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

interface SecurityGuiToUserFacadeInterface
{
    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username);
}
