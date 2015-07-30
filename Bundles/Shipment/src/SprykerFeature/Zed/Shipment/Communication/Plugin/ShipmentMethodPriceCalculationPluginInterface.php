<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface ShipmentMethodPriceCalculationPluginInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function getPrice(OrderTransfer $orderTransfer);
}
