<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\ShipmentInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(CartInterface $cartTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedStub()
            ->getAvailableMethods($cartTransfer)
            ;
    }
}
