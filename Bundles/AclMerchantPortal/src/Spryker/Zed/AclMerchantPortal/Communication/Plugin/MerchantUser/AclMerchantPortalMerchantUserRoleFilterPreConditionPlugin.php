<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserRoleFilterPreConditionPluginInterface;

/**
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Communication\AclMerchantPortalCommunicationFactory getFactory()
 */
class AclMerchantPortalMerchantUserRoleFilterPreConditionPlugin extends AbstractPlugin implements MerchantUserRoleFilterPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the filtered role is not configured as Backoffice login authentication role.
     * - Returns false if the user has ACL group with Backoffice access, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $role
     *
     * @return bool
     */
    public function checkCondition(UserTransfer $userTransfer, string $role): bool
    {
        return $this->getFacade()->checkUserRoleFilterCondition($userTransfer, $role);
    }
}
