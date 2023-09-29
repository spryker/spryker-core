<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentTypesRestApi\Business\ShipmentTypesRestApiFacadeInterface getFacade()
 */
class ItemShipmentTypeQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `Shipment.method` is empty for each element in `QuoteTransfer.items`.
     * - Expects QuoteTransfer.items.shipment.method.idShipmentMethod to be set.
     * - Gets available shipment methods for the provided quote.
     * - Maps shipment types taken from shipment methods to `Quote.items.shipmentType`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function map(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);
    }
}
