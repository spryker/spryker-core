<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service\Zed;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class ShipmentStub implements ShipmentStubInterface
{

    /**
     * @var ZedRequestClient
     */
    private $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param CartInterface $cartTransfer
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(CartInterface $cartTransfer)
    {
        return $this->zedStub->call('/shipment/gateway/get-available-methods', $cartTransfer, null, true);
    }
}
