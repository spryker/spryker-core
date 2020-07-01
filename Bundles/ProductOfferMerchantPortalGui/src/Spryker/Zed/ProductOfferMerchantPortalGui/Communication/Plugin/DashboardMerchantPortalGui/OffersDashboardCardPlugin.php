<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui;

use Generated\Shared\Transfer\DashboardCardTransfer;
use Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\DashboardCardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class OffersDashboardCardPlugin extends AbstractPlugin implements DashboardCardPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns the data for displaying the Product offers card.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer
    {
        return $this->getFactory()->createOffersDashboardCardProvider()->getDashboardCard();
    }
}
