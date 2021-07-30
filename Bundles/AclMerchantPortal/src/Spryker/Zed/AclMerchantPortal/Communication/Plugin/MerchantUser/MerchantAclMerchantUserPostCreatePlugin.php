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
 */
class MerchantAclMerchantUserPostCreatePlugin extends AbstractPlugin implements MerchantUserPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates ACL group for provided merchant user.
     * - Creates ACL role, ACL rules, ACL entity rules for provided merchant user.
     * - Creates ACL entity segment for provided merchant user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function postCreate(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        return $this->getFacade()->createMerchantUserAclData($merchantUserTransfer);
    }
}
