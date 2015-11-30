<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface ShipmentOrderSaverInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveShipmentForOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse);

}
