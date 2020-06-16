<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui;

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
     * - Provides the Product offers card title.
     *
     * @api
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getFactory()->createOffersDashboardCardDataProvider()->getTitle();
    }

    /**
     * {@inheritDoc}
     * - Provides the Product offers card content.
     *
     * @api
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->getFactory()->createOffersDashboardCardDataProvider()->getContent();
    }

    /**
     * {@inheritDoc}
     * - Provides the Product offers card buttons.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardActionButtonTransfer[]
     */
    public function getActionButtons(): array
    {
        return $this->getFactory()->createOffersDashboardCardDataProvider()->getActionButtons();
    }
}
