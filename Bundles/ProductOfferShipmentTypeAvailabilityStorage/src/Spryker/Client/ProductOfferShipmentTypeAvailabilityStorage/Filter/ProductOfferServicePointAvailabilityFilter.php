<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReaderInterface;

class ProductOfferServicePointAvailabilityFilter implements ProductOfferServicePointAvailabilityFilterInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @param \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     */
    public function __construct(ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader)
    {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function filterProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer,
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        $productOfferServicePointAvailabilityConditionsTransfer = $productOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();
        $shipmentTypeStorageTransfer = $this->shipmentTypeStorageReader->findShipmentTypeStorageByProductOfferServicePointAvailabilityConditionsTransfer(
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers */
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        if (
            !$shipmentTypeStorageTransfer
            || !$productOfferServicePointAvailabilityResponseItemTransfers->count()
        ) {
            return $productOfferServicePointAvailabilityCollectionTransfer;
        }

        $filteredProductOfferServicePointAvailabilityResponseItemTransfers = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            if (!$this->hasShipmentTypeUuid($productOfferServicePointAvailabilityResponseItemTransfer, $shipmentTypeStorageTransfer)) {
                continue;
            }

            $filteredProductOfferServicePointAvailabilityResponseItemTransfers[] = $productOfferServicePointAvailabilityResponseItemTransfer;
        }

        return $productOfferServicePointAvailabilityCollectionTransfer->setProductOfferServicePointAvailabilityResponseItems(
            new ArrayObject($filteredProductOfferServicePointAvailabilityResponseItemTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     *
     * @return bool
     */
    protected function hasShipmentTypeUuid(
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer,
        ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
    ): bool {
        /** @deprecated Exists for Backward Compatibility reasons only. */
        if (
            !$productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferStorageOrFail()->getShipmentTypes()->count()
            && $shipmentTypeStorageTransfer->getKeyOrFail() === static::SHIPMENT_TYPE_DELIVERY
        ) {
            return true;
        }

        foreach ($productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferStorageOrFail()->getShipmentTypes() as $shipmentTypeTransfer) {
            if ($shipmentTypeTransfer->getUuidOrFail() === $shipmentTypeStorageTransfer->getUuidOrFail()) {
                return true;
            }
        }

        return false;
    }
}
