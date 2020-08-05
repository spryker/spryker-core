<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionMerchantPortalGui\Communication\Plugin\SalesMerchantPortalGui;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOptionMerchantPortalGui\Communication\ProductOptionMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductOptionMerchantOrderItemTableExpanderPlugin extends AbstractPlugin implements MerchantOrderItemTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands MerchantOrderItemTable with Product options column configuration.
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
     * - Expands MerchantOrderItemTable with Product options column data.
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
