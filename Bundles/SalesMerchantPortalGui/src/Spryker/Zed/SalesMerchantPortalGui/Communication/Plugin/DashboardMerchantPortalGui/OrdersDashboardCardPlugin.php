<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui;

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
     * - Provides the Merchant orders card title HTML.
     *
     * @api
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getFactory()->createOrdersDashboardCardDataProvider()->getTitle();
    }

    /**
     * {@inheritDoc}
     * - Provides the Merchant orders card content HTML.
     *
     * @api
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->getFactory()->createOrdersDashboardCardDataProvider()->getContent();
    }

    /**
     * {@inheritDoc}
     * - Provides the Merchant orders card buttons.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardActionButtonTransfer[]
     */
    public function getActionButtons(): array
    {
        return $this->getFactory()->createOrdersDashboardCardDataProvider()->getActionButtons();
    }
}
