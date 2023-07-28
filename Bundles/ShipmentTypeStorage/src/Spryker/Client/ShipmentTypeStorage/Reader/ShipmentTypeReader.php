<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var \Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @var array<\Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface>
     */
    protected array $availableShipmentTypeFilterPlugins;

    /**
     * @param \Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param array<\Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface> $availableShipmentTypeFilterPlugins
     */
    public function __construct(
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        array $availableShipmentTypeFilterPlugins
    ) {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->availableShipmentTypeFilterPlugins = $availableShipmentTypeFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getAvailableShipmentTypes(QuoteTransfer $quoteTransfer): ShipmentTypeCollectionTransfer
    {
        $storeName = $quoteTransfer->getStoreOrFail()->getNameOrFail();
        $shipmentTypeStorageCollectionTransfer = $this->getStoreShipmentTypeStorages($storeName);

        $filteredShipmentTypeStorageCollection = $this->executeAvailableShipmentTypeFilterPlugins(
            $shipmentTypeStorageCollectionTransfer,
            $quoteTransfer,
        );

        $filteredShipmentTypeStorageCollection = $this->addDeliveryShipmentTypeIfExists(
            $filteredShipmentTypeStorageCollection,
            $shipmentTypeStorageCollectionTransfer,
        );

        return $this->mapShipmentTypeStorageCollectionTransferToShipmentTypeCollectionTransfer(
            $filteredShipmentTypeStorageCollection,
            new ShipmentTypeCollectionTransfer(),
        );
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function getStoreShipmentTypeStorages(string $storeName): ShipmentTypeStorageCollectionTransfer
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($storeName);

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);

        return $this->shipmentTypeStorageReader->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    protected function mapShipmentTypeStorageCollectionTransferToShipmentTypeCollectionTransfer(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ShipmentTypeCollectionTransfer {
        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            $shipmentTypeCollectionTransfer->addShipmentType(
                (new ShipmentTypeTransfer())->fromArray($shipmentTypeStorageTransfer->toArray(), true),
            );
        }

        return $shipmentTypeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function executeAvailableShipmentTypeFilterPlugins(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        foreach ($this->availableShipmentTypeFilterPlugins as $availableShipmentTypeFilterPlugin) {
            $shipmentTypeStorageCollectionTransfer = $availableShipmentTypeFilterPlugin->filter(
                $shipmentTypeStorageCollectionTransfer,
                $quoteTransfer,
            );
        }

        return $shipmentTypeStorageCollectionTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $filteredShipmentTypeStorageCollection
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function addDeliveryShipmentTypeIfExists(
        ShipmentTypeStorageCollectionTransfer $filteredShipmentTypeStorageCollection,
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        $deliveryShipmentType = null;

        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            if ($shipmentTypeStorageTransfer->getKeyOrFail() === static::SHIPMENT_TYPE_DELIVERY) {
                $deliveryShipmentType = $shipmentTypeStorageTransfer;

                break;
            }
        }

        if (!$deliveryShipmentType) {
            return $filteredShipmentTypeStorageCollection;
        }

        foreach ($filteredShipmentTypeStorageCollection->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            if ($shipmentTypeStorageTransfer->getKeyOrFail() === static::SHIPMENT_TYPE_DELIVERY) {
                return $filteredShipmentTypeStorageCollection;
            }
        }

        return $filteredShipmentTypeStorageCollection->addShipmentTypeStorage($deliveryShipmentType);
    }
}
