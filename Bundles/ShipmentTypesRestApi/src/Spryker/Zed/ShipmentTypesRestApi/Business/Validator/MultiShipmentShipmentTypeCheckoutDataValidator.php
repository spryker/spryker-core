<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface;

class MultiShipmentShipmentTypeCheckoutDataValidator extends AbstractShipmentTypeCheckoutDataValidator implements ShipmentTypeCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface
     */
    protected ShipmentMethodReaderInterface $shipmentMethodReader;

    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface
     */
    protected ShipmentTypeCheckoutErrorCreatorInterface $shipmentTypeCheckoutErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface $shipmentMethodReader
     * @param \Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface $shipmentTypeCheckoutErrorCreator
     */
    public function __construct(
        ShipmentMethodReaderInterface $shipmentMethodReader,
        ShipmentTypeCheckoutErrorCreatorInterface $shipmentTypeCheckoutErrorCreator
    ) {
        $this->shipmentMethodReader = $shipmentMethodReader;
        $this->shipmentTypeCheckoutErrorCreator = $shipmentTypeCheckoutErrorCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateShipmentTypeCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        if (!$this->hasAssignedShipmentMethods($checkoutDataTransfer)) {
            return $checkoutResponseTransfer;
        }

        $shipmentMethodTransfersIndexedById = $this->shipmentMethodReader->getShipmentMethodTransfersIndexedByIdShipmentMethod();
        foreach ($checkoutDataTransfer->getShipments() as $shipmentTransfer) {
            $idShipmentMethod = $shipmentTransfer->getIdShipmentMethod();
            if (!$idShipmentMethod) {
                continue;
            }
            $shipmentMethodTransfer = $shipmentMethodTransfersIndexedById[$idShipmentMethod] ?? null;
            if (!$shipmentMethodTransfer || !$shipmentMethodTransfer->getShipmentType()) {
                continue;
            }
            $storeTransfer = $checkoutDataTransfer->getQuoteOrFail()->getStoreOrFail();
            if ($this->isValidShipmentType($shipmentMethodTransfer->getShipmentTypeOrFail(), $storeTransfer)) {
                continue;
            }

            $checkoutErrorTransfer = $this->shipmentTypeCheckoutErrorCreator->createShipmentTypeNotAvailableCheckoutErrorTransfer(
                $shipmentMethodTransfer->getShipmentTypeOrFail(),
            );
            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return bool
     */
    protected function hasAssignedShipmentMethods(CheckoutDataTransfer $checkoutDataTransfer): bool
    {
        foreach ($checkoutDataTransfer->getShipments() as $shipmentTransfer) {
            if ($shipmentTransfer->getIdShipmentMethod()) {
                return true;
            }
        }

        return false;
    }
}
