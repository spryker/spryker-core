<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\CustomerAddressInterface;

interface ShipmentMethodPriceCalculationPluginInterface
{

    /**
     * @param CartInterface $cartTransfer
     * @param CustomerAddressInterface $shippingAddress
     *
     * @return int
     */
    public function getPrice(CartInterface $cartTransfer, CustomerAddressInterface $shippingAddress = null);
}
