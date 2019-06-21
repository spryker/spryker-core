<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ShipmentHash;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface;

class ShipmentHashGenerator implements ShipmentHashGeneratorInterface
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
            $this->prepareIdShipmentMethod($shipmentTransfer),
            $this->prepareShippingAddressKey($shipmentTransfer),
            $shipmentTransfer->getRequestedDeliveryDate()
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function prepareShippingAddressKey(ShipmentTransfer $shipmentTransfer): string
    {
        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer === null) {
            return '';
        }

        return $this->customerService->getUniqueAddressKey($shipmentTransfer->getShippingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function prepareIdShipmentMethod(ShipmentTransfer $shipmentTransfer): string
    {
        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer === null) {
            return '';
        }

        return (string)$shipmentMethodTransfer->getIdShipmentMethod();
    }
}
