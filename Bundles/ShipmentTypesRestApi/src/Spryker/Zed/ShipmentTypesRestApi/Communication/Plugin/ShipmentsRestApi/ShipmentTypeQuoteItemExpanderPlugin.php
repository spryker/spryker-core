<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\QuoteItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypesRestApi\Business\ShipmentTypesRestApiFacadeInterface getFacade()
 */
class ShipmentTypeQuoteItemExpanderPlugin extends AbstractPlugin implements QuoteItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `ShipmentTransfer.method` is empty for each element in `QuoteTransfer.items`.
     * - Expects `QuoteTransfer.items.shipment.method.idShipmentMethod` to be set.
     * - Gets available shipment methods for the provided quote.
     * - Expands items with shipment types taken from shipment methods to `QuoteTransfer.items.shipmentType`.
     * - Expands `QuoteTransfer.items.shipment.shipmentTypeUuid` from `QuoteTransfer.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteItems($quoteTransfer);
    }
}
