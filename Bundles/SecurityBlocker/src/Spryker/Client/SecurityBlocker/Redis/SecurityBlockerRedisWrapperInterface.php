<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Redis;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;

interface SecurityBlockerRedisWrapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer;
}
