<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentMethodDeliveryTimePluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getTime(QuoteTransfer $quoteTransfer);

}
