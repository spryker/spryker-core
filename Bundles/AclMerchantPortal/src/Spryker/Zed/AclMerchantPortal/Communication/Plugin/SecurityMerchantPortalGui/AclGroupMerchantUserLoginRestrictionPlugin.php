<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication\Plugin\SecurityMerchantPortalGui;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserLoginRestrictionPluginInterface;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Communication\AclMerchantPortalCommunicationFactory getFactory()
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 */
class AclGroupMerchantUserLoginRestrictionPlugin extends AbstractPlugin implements MerchantUserLoginRestrictionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantUserTransfer.idUser` to be provided.
     * - Returns `true` if the given `MerchantUser` transfer has a group with a `root_group` name, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    public function isRestricted(MerchantUserTransfer $merchantUserTransfer): bool
    {
        return $this->getFacade()->isMerchantUserLoginRestricted($merchantUserTransfer);
    }
}
