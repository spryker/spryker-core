<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ShipmentHash;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToUtilEncodingServiceInterface;
use Spryker\Service\Shipment\ShipmentConfig;

class ShipmentHashGenerator implements ShipmentHashGeneratorInterface
{
    /**
     * @var string
     */
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s-%s';

    /**
     * @var \Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \Spryker\Service\Shipment\ShipmentConfig
     */
    protected $shipmentConfig;

    /**
     * @var \Spryker\Service\Shipment\Dependency\Service\ShipmentToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface $customerService
     * @param \Spryker\Service\Shipment\ShipmentConfig $shipmentConfig
     * @param \Spryker\Service\Shipment\Dependency\Service\ShipmentToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ShipmentToCustomerServiceInterface $customerService,
        ShipmentConfig $shipmentConfig,
        ShipmentToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->customerService = $customerService;
        $this->shipmentConfig = $shipmentConfig;
        $this->utilEncodingService = $utilEncodingService;
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
            $this->prepareShipmentMethodKey($shipmentTransfer),
            $this->prepareShippingAddressKey($shipmentTransfer),
            $shipmentTransfer->getRequestedDeliveryDate(),
            $this->getShipmentAdditionalKeyData($shipmentTransfer),
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
    protected function prepareShipmentMethodKey(ShipmentTransfer $shipmentTransfer): string
    {
        $shipmentMethodTransfer = $shipmentTransfer->getMethod();

        if ($shipmentMethodTransfer === null) {
            return '';
        }

        return $this->getShipmentMethodKeyEncodedData($shipmentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return string
     */
    protected function getShipmentMethodKeyEncodedData(ShipmentMethodTransfer $shipmentMethodTransfer): string
    {
        $shipmentMethodKeyData = [];
        $shipmentMethodData = $shipmentMethodTransfer->toArray(false, true);

        foreach ($this->shipmentConfig->getShipmentMethodHashFields() as $fieldName) {
            if (empty($shipmentMethodData[$fieldName])) {
                continue;
            }

            $shipmentMethodKeyData[$fieldName] = $shipmentMethodData[$fieldName];
        }

        return $this->utilEncodingService->encodeJson($shipmentMethodKeyData);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentAdditionalKeyData(ShipmentTransfer $shipmentTransfer): string
    {
        $shipmentAdditionalKeyData = [];
        $shipmentData = $shipmentTransfer->toArray(false, true);

        foreach ($this->shipmentConfig->getShipmentHashFields() as $fieldName) {
            if (empty($shipmentData[$fieldName])) {
                continue;
            }

            $shipmentAdditionalKeyData[$fieldName] = $shipmentData[$fieldName];
        }

        return $this->utilEncodingService->encodeJson($shipmentAdditionalKeyData);
    }
}
