<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityMerchantPortalGui\Communication\Plugin\ProductMerchantPortalGui;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\AvailabilityMerchantPortalGui\Communication\AvailabilityMerchantPortalGuiCommunicationFactory getFactory()
 */
class TotalProductAvailabilityProductConcreteTableExpanderPlugin extends AbstractPlugin implements ProductConcreteTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductConcreteTable with Available stock column configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        return $this->getFactory()->createProductConcreteTableExpander()->expandConfiguration($guiTableConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands ProductConcreteTable with Available stock column data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        return $this->getFactory()->createProductConcreteTableExpander()->expandDataResponse($guiTableDataResponseTransfer);
    }
}
