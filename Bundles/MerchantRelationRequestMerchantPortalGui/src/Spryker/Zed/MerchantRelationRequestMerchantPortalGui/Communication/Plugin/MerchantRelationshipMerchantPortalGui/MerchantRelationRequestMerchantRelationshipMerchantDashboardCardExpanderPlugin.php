<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\MerchantRelationshipMerchantPortalGui;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin\MerchantRelationshipMerchantDashboardCardExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantRelationRequestMerchantRelationshipMerchantDashboardCardExpanderPlugin extends AbstractPlugin implements MerchantRelationshipMerchantDashboardCardExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands provided `MerchantDashboardCardTransfer` with Merchant relation request data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function expand(MerchantDashboardCardTransfer $merchantDashboardCardTransfer): MerchantDashboardCardTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationRequestMerchantDashboardCardExpander()
            ->expand($merchantDashboardCardTransfer);
    }
}
