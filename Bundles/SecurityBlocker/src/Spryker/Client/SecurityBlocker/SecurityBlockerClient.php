<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
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
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function incrementLoginAttemptCount(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        return $this->getFactory()
            ->createSecurityBlockerStorageWriter()
            ->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function isAccountBlocked(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        return $this->getFactory()
            ->createSecurityBlockerStorageChecker()
            ->isAccountBlocked($securityCheckAuthContextTransfer);
    }
}
