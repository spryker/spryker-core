<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGeneratorInterface;

class ProductOfferStorageExpander implements ProductOfferStorageExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_SHIPMENT_TYPE_UUIDS = 'shipmentTypeUuids';

    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGeneratorInterface
     */
    protected ProductOfferShipmentTypeKeyGeneratorInterface $productOfferShipmentTypeKeyGenerator;

    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientInterface
     */
    protected ProductOfferShipmentTypeStorageToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface
     */
    protected ProductOfferShipmentTypeStorageToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface
     */
    protected ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface $shipmentTypeStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferShipmentTypeStorage\Generator\ProductOfferShipmentTypeKeyGeneratorInterface $productOfferShipmentTypeKeyGenerator
     * @param \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     */
    public function __construct(
        ProductOfferShipmentTypeKeyGeneratorInterface $productOfferShipmentTypeKeyGenerator,
        ProductOfferShipmentTypeStorageToStorageClientInterface $storageClient,
        ProductOfferShipmentTypeStorageToStoreClientInterface $storeClient,
        ProductOfferShipmentTypeStorageToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
    ) {
        $this->productOfferShipmentTypeKeyGenerator = $productOfferShipmentTypeKeyGenerator;
        $this->storageClient = $storageClient;
        $this->storeClient = $storeClient;
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expandProductOfferStorageWithShipmentTypes(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        $currentStoreName = $this->storeClient->getCurrentStore()->getNameOrFail();
        $productOfferShipmentTypeKey = $this
            ->productOfferShipmentTypeKeyGenerator
            ->generateProductOfferShipmentTypeKey($productOfferStorageTransfer, $currentStoreName);

        $productOfferShipmentTypeData = $this->storageClient->get($productOfferShipmentTypeKey);
        if (!$productOfferShipmentTypeData) {
            return $productOfferStorageTransfer;
        }

        $shipmentTypeUuids = $productOfferShipmentTypeData[static::KEY_SHIPMENT_TYPE_UUIDS] ?? [];
        if (!$shipmentTypeUuids) {
            return $productOfferStorageTransfer;
        }

        return $productOfferStorageTransfer->setShipmentTypes(
            $this->getShipmentTypeStorageTransfers($shipmentTypeUuids, $currentStoreName),
        );
    }

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function getShipmentTypeStorageTransfers(array $shipmentTypeUuids, string $storeName): ArrayObject
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setUuids($shipmentTypeUuids)
            ->setStoreName($storeName);
        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);

        return $this
            ->shipmentTypeStorageClient
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer)
            ->getShipmentTypeStorages();
    }
}
