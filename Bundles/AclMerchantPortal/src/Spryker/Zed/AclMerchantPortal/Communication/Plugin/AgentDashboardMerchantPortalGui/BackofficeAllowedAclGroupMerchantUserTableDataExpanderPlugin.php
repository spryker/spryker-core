<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication\Plugin\AgentDashboardMerchantPortalGui;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserTableDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 * @method \Spryker\Zed\AclMerchantPortal\Communication\AclMerchantPortalCommunicationFactory getFactory()
 */
class BackofficeAllowedAclGroupMerchantUserTableDataExpanderPlugin extends AbstractPlugin implements MerchantUserTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Verifies whether users are associated with ACL groups listed in
     * {@link \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig::getBackofficeAllowedAclGroupNames()}.
     * - Sets null to the response data under the `assistUser` keys for users belonging to these groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expand(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        return $this->getFacade()->expandAgentDashboardMerchantUserTableData($guiTableDataResponseTransfer);
    }
}
