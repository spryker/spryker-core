<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui;

use Generated\Shared\Transfer\DashboardCardTransfer;
use Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\DashboardCardPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig getConfig()
 */
class OrdersDashboardCardPlugin extends AbstractPlugin implements DashboardCardPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns the data for displaying the Merchant orders card.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer
    {
        return $this->getFactory()->createOrdersDashboardCardProvider()->getDashboardCard();
    }
}
