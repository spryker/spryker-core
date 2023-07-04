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
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface;

class ProductOfferShipmentTypeStorageWriter implements ProductOfferShipmentTypeStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER = 'spy_product_offer_shipment_type.fk_product_offer';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_STORE_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeStoreTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_type_store.fk_shipment_type';

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
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter\ProductOfferShipmentTypeStorageDeleterInterface $productOfferShipmentTypeStorageDeleter
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader,
        ProductOfferShipmentTypeStorageDeleterInterface $productOfferShipmentTypeStorageDeleter,
        ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager,
        ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor,
        ProductOfferShipmentTypeStorageToStoreFacadeInterface $storeFacade,
        ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->productOfferShipmentTypeReader = $productOfferShipmentTypeReader;
        $this->productOfferShipmentTypeStorageDeleter = $productOfferShipmentTypeStorageDeleter;
        $this->productOfferShipmentTypeStorageEntityManager = $productOfferShipmentTypeStorageEntityManager;
        $this->productOfferShipmentTypeExtractor = $productOfferShipmentTypeExtractor;
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER,
        );

        if ($productOfferIds !== []) {
            $this->writeCollectionByProductOfferIds(array_unique($productOfferIds));

            return;
        }

        $productOfferShipmentTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCollectionByProductOfferShipmentTypeIds($productOfferShipmentTypeIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCollectionByProductOfferIds(array_unique($productOfferIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_PRODUCT_OFFER_STORE_FK_PRODUCT_OFFER,
        );

        $this->writeCollectionByProductOfferIds(array_unique($productOfferIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCollectionByShipmentTypeIds($shipmentTypeIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, static::COL_FK_SHIPMENT_TYPE);

        $this->writeCollectionByShipmentTypeIds($shipmentTypeIds);
    }

    /**
     * @param list<int> $productOfferShipmentTypeIds
     *
     * @return void
     */
    protected function writeCollectionByProductOfferShipmentTypeIds(array $productOfferShipmentTypeIds): void
    {
        $productOfferShipmentTypeIterator = $this->productOfferShipmentTypeReader
            ->getProductOfferShipmentTypeIteratorByProductOfferShipmentTypeIds($productOfferShipmentTypeIds);

        foreach ($productOfferShipmentTypeIterator as $productOfferShipmentTypeTransfers) {
            $this->writeCollection($productOfferShipmentTypeTransfers);
        }
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return void
     */
    protected function writeCollectionByProductOfferIds(array $productOfferIds): void
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

            $this->writeCollection($productOfferShipmentTypeTransfers);
        }
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    protected function writeCollectionByShipmentTypeIds(array $shipmentTypeIds): void
    {
        $productOfferShipmentTypeTransfersIterator = $this->productOfferShipmentTypeReader->getProductOfferShipmentTypeIteratorByShipmentTypeIds(
            $shipmentTypeIds,
        );

        foreach ($productOfferShipmentTypeTransfersIterator as $productOfferShipmentTypeTransfers) {
            $this->writeCollection($productOfferShipmentTypeTransfers);
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return void
     */
    protected function writeCollection(ArrayObject $productOfferShipmentTypeTransfers): void
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
