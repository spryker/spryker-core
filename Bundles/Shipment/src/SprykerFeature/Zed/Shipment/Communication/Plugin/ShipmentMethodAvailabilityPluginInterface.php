<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\CustomerAddressInterface;

interface ShipmentMethodAvailabilityPluginInterface
{

    /**
     * @param CartInterface $cartInterface
     * @param CustomerAddressInterface $shippingAddress
     *
     * @return bool
     */
    public function isAvailable(CartInterface $cartInterface, CustomerAddressInterface $shippingAddress = null);
}
