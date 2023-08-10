<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface;

class ShipmentTypeAvailableCheckoutValidationRule implements ShipmentTypeCheckoutValidationRuleInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface
     */
    protected SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator
     */
    public function __construct(
        ShipmentTypeReaderInterface $shipmentTypeReader,
        SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator
    ) {
        $this->shipmentTypeReader = $shipmentTypeReader;
        $this->salesShipmentTypeValidationErrorCreator = $salesShipmentTypeValidationErrorCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $indexedShipmentTypeTransfers = $this->getShipmentTypeTransfersIndexedByShipmentTypeUuid($quoteTransfer->getItems());

        $shipmentTypeUuids = array_keys($indexedShipmentTypeTransfers);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getActiveShipmentTypeCollection(
            $shipmentTypeUuids,
            $quoteTransfer->getStoreOrFail()->getNameOrFail(),
        );

        $retrievedShipmentTypeUuids = $this->extractShipmentTypeUuidsFromShipmentTypeTransfers(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
        );

        $unavailableShipmentTypeUuids = array_diff($shipmentTypeUuids, $retrievedShipmentTypeUuids);
        if ($unavailableShipmentTypeUuids === []) {
            return true;
        }

        foreach ($unavailableShipmentTypeUuids as $shipmentTypeUuid) {
            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($this->salesShipmentTypeValidationErrorCreator->createCheckoutErrorTransfer($indexedShipmentTypeTransfers[$shipmentTypeUuid]));
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isShipmentMethodShipmentTypeProvided(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() !== null
            && $itemTransfer->getShipmentOrFail()->getMethod() !== null
            && $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType() !== null;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersIndexedByShipmentTypeUuid(ArrayObject $itemTransfers): array
    {
        $indexedShipmentTypeTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (!$this->isShipmentMethodShipmentTypeProvided($itemTransfer)) {
                continue;
            }

            $shipmentTypeTransfer = $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentTypeOrFail();
            $indexedShipmentTypeTransfers[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer;
        }

        return $indexedShipmentTypeTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeUuidsFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array
    {
        $shipmentTypeUuids = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeUuids[] = $shipmentTypeTransfer->getUuidOrFail();
        }

        return $shipmentTypeUuids;
    }
}
