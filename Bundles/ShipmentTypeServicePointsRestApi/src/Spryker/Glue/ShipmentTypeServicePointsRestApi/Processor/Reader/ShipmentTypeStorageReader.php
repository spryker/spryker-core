<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;

class ShipmentTypeStorageReader implements ShipmentTypeStorageReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig
     */
    protected ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface
     */
    protected ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface
     */
    protected ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer|null
     */
    protected static ?ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer = null;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     */
    public function __construct(
        ShipmentTypeServicePointsRestApiConfig $shipmentTypeServicePointsRestApiConfig,
        ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient,
        ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
    ) {
        $this->shipmentTypeServicePointsRestApiConfig = $shipmentTypeServicePointsRestApiConfig;
        $this->storeClient = $storeClient;
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
    }

    /**
     * @param list<int> $shipmentMethodIds
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod(array $shipmentMethodIds): array
    {
        $shipmentTypeStorageCollectionTransfer = $this->getShipmentTypeStorageCollectionTransfer();

        $shipmentTypeStorageTransfersIndexedByIdShipmentMethod = [];
        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            if (array_intersect($shipmentTypeStorageTransfer->getShipmentMethodIds(), $shipmentMethodIds) === []) {
                continue;
            }
            if (!$this->isApplicable($shipmentTypeStorageTransfer)) {
                continue;
            }
            $shipmentTypeStorageTransfersIndexedByIdShipmentMethod = $this->extendIndexedShipmentTypeStorageTransfers(
                $shipmentTypeStorageTransfer,
                $shipmentMethodIds,
                $shipmentTypeStorageTransfersIndexedByIdShipmentMethod,
            );
        }

        return $shipmentTypeStorageTransfersIndexedByIdShipmentMethod;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function getShipmentTypeStorageCollectionTransfer(): ShipmentTypeStorageCollectionTransfer
    {
        if (static::$shipmentTypeStorageCollectionTransfer === null) {
            static::$shipmentTypeStorageCollectionTransfer = $this
                ->shipmentTypeStorageClient
                ->getShipmentTypeStorageCollection(
                    $this->createShipmentTypeStorageCriteriaTransfer(),
                );
        }

        return static::$shipmentTypeStorageCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer
     */
    protected function createShipmentTypeStorageCriteriaTransfer(): ShipmentTypeStorageCriteriaTransfer
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($this->storeClient->getCurrentStore()->getName());

        return (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     *
     * @return bool
     */
    protected function isApplicable(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer): bool
    {
        return in_array(
            $shipmentTypeStorageTransfer->getKeyOrFail(),
            $this->shipmentTypeServicePointsRestApiConfig->getApplicableShipmentTypeKeysForShippingAddress(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     * @param list<int> $shipmentMethodIds
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfersIndexedByIdShipmentMethod
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function extendIndexedShipmentTypeStorageTransfers(
        ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer,
        array $shipmentMethodIds,
        array $shipmentTypeStorageTransfersIndexedByIdShipmentMethod
    ): array {
        foreach ($shipmentTypeStorageTransfer->getShipmentMethodIds() as $idShipmentMethod) {
            if (!in_array($idShipmentMethod, $shipmentMethodIds, true)) {
                continue;
            }
            $shipmentTypeStorageTransfersIndexedByIdShipmentMethod[$idShipmentMethod] = $shipmentTypeStorageTransfer;
        }

        return $shipmentTypeStorageTransfersIndexedByIdShipmentMethod;
    }
}
