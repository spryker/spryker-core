<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentOrderSaverInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

}
