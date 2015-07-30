<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface ShipmentMethodDeliveryTimePluginInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function getTime(OrderTransfer $orderTransfer);
}
