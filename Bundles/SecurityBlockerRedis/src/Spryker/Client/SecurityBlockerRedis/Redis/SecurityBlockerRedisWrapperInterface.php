<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerRedis\Redis;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Generated\Shared\Transfer\StorageScanResultTransfer;

interface SecurityBlockerRedisWrapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlockerRedis\Exception\SecurityBlockerRedisException
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
