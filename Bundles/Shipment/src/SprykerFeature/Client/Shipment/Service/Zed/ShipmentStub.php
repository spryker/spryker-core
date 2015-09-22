<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service\Zed;

use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class ShipmentStub implements ShipmentStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability)
    {
        return $this->zedStub->call('/shipment/gateway/get-available-methods', $shipmentMethodAvailability, null, true);
    }

}
