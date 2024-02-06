<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\SecurityMerchantPortalGui;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserCriteriaExpanderPluginInterface;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 */
class AgentMerchantUserCriteriaExpanderPlugin extends AbstractPlugin implements MerchantUserCriteriaExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - If `AgentSecurityMerchantPortalGuiConfig.getRoleMerchantAgent()` and `AgentSecurityMerchantPortalGuiConfig.getRoleAllowedToSwitch()`
     * roles are granted sets `null` to `MerchantUserCriteria.status` and `MerchantUserCriteria.merchantStatus`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    public function expand(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): MerchantUserCriteriaTransfer
    {
        return $this->getFactory()->createMerchantUserCriteriaExpander()->expand($merchantUserCriteriaTransfer);
    }
}
