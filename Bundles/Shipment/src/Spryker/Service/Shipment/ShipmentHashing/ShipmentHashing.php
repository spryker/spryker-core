<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ShipmentHashing;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface;

class ShipmentHashing implements ShipmentHashingInterface
{
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s';

    /**
     * @var \Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @param \Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface $customerService
     */
    public function __construct(ShipmentToCustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string
    {
        return md5(sprintf(
            static::SHIPMENT_TRANSFER_KEY_PATTERN,
            $shipmentTransfer->getMethod() !== null ? $shipmentTransfer->getMethod()->getIdShipmentMethod() : '',
            $shipmentTransfer->getShippingAddress() !== null ?
                $this->customerService->getUniqueAddressKey($shipmentTransfer->getShippingAddress()) : '',
            $shipmentTransfer->getRequestedDeliveryDate()
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $shipmentHashKey
     *
     * @return bool
     */
    public function isShipmentEqualShipmentHashKey(ShipmentTransfer $shipmentTransfer, string $shipmentHashKey): bool
    {
        return $this->getShipmentHashKey($shipmentTransfer) === $shipmentHashKey;
    }
}
