<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Delegator;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface;

class SecurityBlockerStorageDelegator implements SecurityBlockerStorageDelegatorInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface
     */
    protected $securityBlockerStorageAdapterPlugin;

    /**
     * @param \Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface $securityBlockerStorageAdapterPlugin
     */
    public function __construct(SecurityBlockerStorageAdapterPluginInterface $securityBlockerStorageAdapterPlugin)
    {
        $this->securityBlockerStorageAdapterPlugin = $securityBlockerStorageAdapterPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->securityBlockerStorageAdapterPlugin->logLoginAttempt($authContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        return $this->securityBlockerStorageAdapterPlugin->getLoginAttempt($authContextTransfer);
    }
}
