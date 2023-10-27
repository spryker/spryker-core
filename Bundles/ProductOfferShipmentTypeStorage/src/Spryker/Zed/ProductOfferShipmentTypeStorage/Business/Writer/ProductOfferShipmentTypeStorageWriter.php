<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter\ProductOfferShipmentTypeStorageDeleterInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface;

class ProductOfferShipmentTypeStorageWriter implements ProductOfferShipmentTypeStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface
     */
    protected ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter\ProductOfferShipmentTypeStorageDeleterInterface
     */
    protected ProductOfferShipmentTypeStorageDeleterInterface $productOfferShipmentTypeStorageDeleter;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface
     */
    protected ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface
     */
    protected ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter\ProductOfferShipmentTypeStorageDeleterInterface $productOfferShipmentTypeStorageDeleter
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader,
        ProductOfferShipmentTypeStorageDeleterInterface $productOfferShipmentTypeStorageDeleter,
        ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager,
        ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor,
        ProductOfferShipmentTypeStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->productOfferShipmentTypeReader = $productOfferShipmentTypeReader;
        $this->productOfferShipmentTypeStorageDeleter = $productOfferShipmentTypeStorageDeleter;
        $this->productOfferShipmentTypeStorageEntityManager = $productOfferShipmentTypeStorageEntityManager;
        $this->productOfferShipmentTypeExtractor = $productOfferShipmentTypeExtractor;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByProductOfferIds(array $productOfferIds): void
    {
        $productOfferShipmentTypeTransfersIterator = $this->productOfferShipmentTypeReader
            ->getProductOfferShipmentTypeIteratorByProductOfferIds($productOfferIds);

        foreach ($productOfferShipmentTypeTransfersIterator as $productOfferShipmentTypeTransfers) {
            $retrievedProductOfferIds = $this->productOfferShipmentTypeExtractor->extractProductOfferIdsFromProductOfferShipmentTypeTransfers(
                $productOfferShipmentTypeTransfers,
            );
            $productOfferIdsToDelete = array_diff($productOfferIds, $retrievedProductOfferIds);
            if ($productOfferIdsToDelete !== []) {
                $this->productOfferShipmentTypeStorageDeleter->deleteProductOfferShipmentTypeStoragesByProductOfferIds($productOfferIdsToDelete);
            }

            $this->writeProductOfferShipmentTypeStorageCollection($productOfferShipmentTypeTransfers);
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollection(ArrayObject $productOfferShipmentTypeTransfers): void
    {
        $storeCollectionTransfer = $this->storeFacade->getStoreCollection(new StoreCriteriaTransfer());
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $this->writeProductOfferShipmentTypeStorageCollectionForStore(
                $productOfferShipmentTypeTransfers,
                $storeTransfer->getNameOrFail(),
            );
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     * @param string $storeName
     *
     * @return void
     */
    protected function writeProductOfferShipmentTypeStorageCollectionForStore(
        ArrayObject $productOfferShipmentTypeTransfers,
        string $storeName
    ): void {
        $productOfferReferencesToDelete = [];
        foreach ($productOfferShipmentTypeTransfers as $productOfferShipmentTypeTransfer) {
            $productOfferReference = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getProductOfferReferenceOrFail();
            if (!$this->hasStoreRelationship($productOfferShipmentTypeTransfer->getProductOfferOrFail()->getStores(), $storeName)) {
                $productOfferReferencesToDelete[] = $productOfferReference;
            }

            $activeShipmentTypeUuidsWithStoreRelation = $this->getActiveShipmentTypeUuidsWithStoreRelation(
                $productOfferShipmentTypeTransfer->getShipmentTypes(),
                $storeName,
            );
            if ($activeShipmentTypeUuidsWithStoreRelation === []) {
                $productOfferReferencesToDelete[] = $productOfferReference;

                continue;
            }

            $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference($productOfferReference)
                ->setShipmentTypeUuids($activeShipmentTypeUuidsWithStoreRelation);

            $this->productOfferShipmentTypeStorageEntityManager->saveProductOfferShipmentTypeStorage(
                $productOfferShipmentTypeStorageTransfer,
                $storeName,
            );
        }

        if ($productOfferReferencesToDelete !== []) {
            $this->productOfferShipmentTypeStorageDeleter->deleteProductOfferShipmentTypeStoragesByProductOfferReferences(
                $productOfferReferencesToDelete,
                $storeName,
            );
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param string $storeName
     *
     * @return list<string>
     */
    protected function getActiveShipmentTypeUuidsWithStoreRelation(ArrayObject $shipmentTypeTransfers, string $storeName): array
    {
        $shipmentTypeUuids = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            if ($shipmentTypeTransfer->getIsActiveOrFail() && $this->hasStoreRelationship($shipmentTypeTransfer->getStoreRelationOrFail()->getStores(), $storeName)) {
                $shipmentTypeUuids[] = $shipmentTypeTransfer->getUuidOrFail();
            }
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     * @param string $storeName
     *
     * @return bool
     */
    protected function hasStoreRelationship(ArrayObject $storeTransfers, string $storeName): bool
    {
        foreach ($storeTransfers as $storeTransfer) {
            if ($storeTransfer->getNameOrFail() === $storeName) {
                return true;
            }
        }

        return false;
    }
}
