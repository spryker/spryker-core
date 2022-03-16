<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

interface SessionUserValidationToUserFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer;

    /**
     * @return bool
     */
    public function hasCurrentUser(): bool;

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername(string $username): UserTransfer;
}
