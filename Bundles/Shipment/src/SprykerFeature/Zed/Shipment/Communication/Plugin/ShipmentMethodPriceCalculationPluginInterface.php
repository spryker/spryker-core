<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;

interface ShipmentMethodPriceCalculationPluginInterface
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @return int
     */
    public function getPrice(CartInterface $cartTransfer);
}
