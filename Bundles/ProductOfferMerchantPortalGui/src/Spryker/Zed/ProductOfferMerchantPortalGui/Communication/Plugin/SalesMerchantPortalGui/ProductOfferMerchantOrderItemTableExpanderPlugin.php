<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Plugin\SalesMerchantPortalGui;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantOrderItemTableExpanderPlugin extends AbstractPlugin implements MerchantOrderItemTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands MerchantOrderItemTable with Merchant SKU and Product offer reference columns configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        return $this->getFactory()->createMerchantOrderItemTableExpander()->expandConfiguration($guiTableConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands MerchantOrderItemTable with Merchant SKU and Product offer reference columns data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        return $this->getFactory()->createMerchantOrderItemTableExpander()->expandDataResponse($guiTableDataResponseTransfer);
    }
}
