<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface ShipmentMethodAvailabilityPluginInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAvailable(OrderTransfer $orderTransfer);
}
