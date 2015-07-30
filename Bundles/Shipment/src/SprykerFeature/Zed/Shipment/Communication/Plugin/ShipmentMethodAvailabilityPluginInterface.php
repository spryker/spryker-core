<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;

interface ShipmentMethodAvailabilityPluginInterface
{

    /**
     * @param CartInterface $cartInterface
     *
     * @return bool
     */
    public function isAvailable(CartInterface $cartInterface);
}
