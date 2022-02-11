<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 */
class ProductApprovalStatusProductTableExpanderPlugin extends AbstractPlugin implements ProductTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands GuiTableConfigurationTransfer with approval status column.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        return $this->getFactory()
            ->createProductApprovalStatusProductTableConfigurationExpander()
            ->expand($guiTableConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands GuiTableDataResponseTransfer.data with approval status column.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        return $this->getFactory()
            ->createProductApprovalStatusProductTableDataResponseExpander()
            ->expand($guiTableDataResponseTransfer);
    }
}
