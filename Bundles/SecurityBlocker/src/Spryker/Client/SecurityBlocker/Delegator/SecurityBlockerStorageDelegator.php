<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Delegator;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;

class SecurityBlockerStorageDelegator implements SecurityBlockerStorageDelegatorInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface
     */
    protected $securityBlockerAdapterPlugin;

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->securityBlockerAdapterPlugin->logLoginAttempt($authContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->securityBlockerAdapterPlugin->getLoginAttempt($authContextTransfer);
    }
}
