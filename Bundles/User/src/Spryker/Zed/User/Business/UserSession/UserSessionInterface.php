<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\UserSession;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

interface UserSessionInterface
{
    /**
     * @return bool
     */
    public function hasCurrentUser(): bool;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function getCurrentUser(): ?UserTransfer;

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    public function getUserSessionMetadata(): MetadataBag;
}
