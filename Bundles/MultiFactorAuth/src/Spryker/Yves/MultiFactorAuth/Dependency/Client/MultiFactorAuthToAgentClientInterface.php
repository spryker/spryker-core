<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Dependency\Client;

use Generated\Shared\Transfer\UserTransfer;

interface MultiFactorAuthToAgentClientInterface
{
    /**
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgent(): UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findAgentByUsername(UserTransfer $userTransfer): ?UserTransfer;
}
