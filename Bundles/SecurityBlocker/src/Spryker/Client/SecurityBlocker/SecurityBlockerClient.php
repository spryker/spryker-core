<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecurityBlocker\SecurityBlockerFactory getFactory()
 */
class SecurityBlockerClient extends AbstractClient implements SecurityBlockerClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function incrementLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->getFactory()
            ->createSecurityBlockerRedisWrapper()
            ->logLoginAttempt($authContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->getFactory()
            ->createSecurityBlockerRedisWrapper()
            ->getLoginAttempt($authContextTransfer);
    }
}
