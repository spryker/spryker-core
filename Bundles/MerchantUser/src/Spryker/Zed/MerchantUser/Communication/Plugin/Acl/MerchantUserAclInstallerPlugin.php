<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Acl;

use Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserAclInstallerPlugin extends AbstractPlugin implements AclInstallerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns Roles required for MerchantUser module.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array
    {
        return $this->getConfig()->getInstallAclRoles();
    }

    /**
     * {@inheritDoc}
     * - Returns Groups required for MerchantUser module.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array
    {
        return $this->getConfig()->getInstallAclGroups();
    }
}
