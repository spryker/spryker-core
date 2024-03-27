<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;
use Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantDashboardCardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\MerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipMerchantDashboardCardPlugin extends AbstractPlugin implements MerchantDashboardCardPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns the data for displaying the Merchant relation related card.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getDashboardCard(): MerchantDashboardCardTransfer
    {
        return $this->getFactory()
            ->createMerchantDashboardCardDataProvider()
            ->getMerchantRelationDashboardCard();
    }
}
