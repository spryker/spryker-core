<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerRedis\Plugin\SecurityBlocker;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface;

/**
 * @method \Spryker\Client\SecurityBlockerRedis\SecurityBlockerRedisClient getClient()
 */
class RedisSecurityBlockerStorageAdapterPlugin extends AbstractPlugin implements SecurityBlockerStorageAdapterPluginInterface
{
    /**
     * {@inheritDoc}
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->getClient()->logLoginAttempt($authContextTransfer);
    }

    /**
     * {@inheritDoc}
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->getClient()->getLoginAttempt($authContextTransfer);
    }
}
