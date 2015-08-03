<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;

interface ShipmentMethodDeliveryTimePluginInterface
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @return int
     */
    public function getTime(CartInterface $cartTransfer);
}
