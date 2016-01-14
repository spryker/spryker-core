<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentMethodPricePluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getPrice(QuoteTransfer $quoteTransfer);

}
