<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeCart\Business\ShipmentTypeCartFacadeInterface getFacade()
 */
class ShipmentTypeQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.items.shipment` transfer property to be set.
     * - Expects `QuoteTransfer.items.shipmentType.uuid` transfer property to be set.
     * - Does nothing if `QuoteTransfer.items.shipmentType` is not provided.
     * - Sets `QuoteTransfer.items.shipment.shipmentTypeUuid` taken from `QuoteTransfer.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteItemsWithShipmentType($quoteTransfer);
    }
}
