<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Communication\AclMerchantPortalCommunicationFactory getFactory()
 */
class MerchantUserAclEntitiesMerchantUserPostCreatePlugin extends AbstractPlugin implements MerchantUserPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantUser.idMerchantUser` to be provided.
     * - Requires `MerchantUser.user.idUser`, `MerchantUser.user.firstName` and `MerchantUser.user.lastName` to be provided.
     * - Requires `MerchantUser.merchant.name` and `MerchantUser.merchant.merchantReference` to be provided.
     * - Creates ACL entity segment for provided merchant user.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface} plugin stack.
     * - Creates ACL role, ACL rules, ACL entity rules, ACL group for provided merchant user.
     * - Finds merchant, product-viewer groups.
     * - Adds merchant user to merchant, product-viewer, merchant-user groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function postCreate(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        return $this->getFacade()->createAclEntitiesForMerchantUser($merchantUserTransfer);
    }
}
