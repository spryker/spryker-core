<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\ShipmentCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface ShipmentOrderSaverInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveShipmentForOrder(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse);

}
