<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\SecurityGui;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserRoleFilterPluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 * @method \Spryker\Zed\MerchantUser\Communication\MerchantUserCommunicationFactory getFactory()
 */
class MerchantUserUserRoleFilterPlugin extends AbstractPlugin implements UserRoleFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters ROLE_BACK_OFFICE_USER to prevent Merchant User login to Backoffice.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<string> $roles
     *
     * @return array<string>
     */
    public function filter(UserTransfer $userTransfer, array $roles): array
    {
        return $this->getFacade()->filterUserRoles($userTransfer, $roles);
    }
}
