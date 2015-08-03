<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\ShipmentInterface;

interface ShipmentClientInterface
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(CartInterface $cartTransfer);
}
