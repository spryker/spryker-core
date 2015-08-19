<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\CustomerAddressInterface;

interface ShipmentMethodTaxCalculationPluginInterface
{

    /**
     * @param CartInterface $cartTransfer
     * @param int $defaultEffectiveTaxRate
     * @param CustomerAddressInterface|null $shippingAddress
     * @return int $defaultEffectiveTaxRate
     */
    public function getTaxRate(CartInterface $cartTransfer, $defaultEffectiveTaxRate, CustomerAddressInterface $shippingAddress = null);
}
