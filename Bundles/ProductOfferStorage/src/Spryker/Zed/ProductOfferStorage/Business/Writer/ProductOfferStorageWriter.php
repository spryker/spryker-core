<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageBusinessFactory getFactory()
 */
class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    use TransactionTrait;

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_REFERENCE = 'spy_product_offer.product_offer_reference';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface
     */
    protected $productOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface
     */
    protected $productOfferStorageDeleter;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected $storeTransfers;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @var array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface>
     */
    protected $productOfferStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     * @param \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface $productOfferStorageDeleter
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface> $productOfferStorageFilterPlugins
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager,
        ProductOfferStorageDeleterInterface $productOfferStorageDeleter,
        ProductOfferStorageToStoreFacadeInterface $storeFacade,
        ProductOfferStorageReaderInterface $productOfferStorageReader,
        array $productOfferStorageFilterPlugins
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
        $this->productOfferStorageDeleter = $productOfferStorageDeleter;
        $this->storeFacade = $storeFacade;
        $this->productOfferStorageReader = $productOfferStorageReader;
        $this->productOfferStorageFilterPlugins = $productOfferStorageFilterPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues(
            $eventTransfers,
            static::COL_PRODUCT_OFFER_REFERENCE,
        );

        if ($productOfferReferences) {
            $productOfferCollectionTransfer = $this->productOfferStorageReader
                ->getProductOfferCollectionByProductOfferReferences($productOfferReferences);

            $this->getTransactionHandler()->handleTransaction(function () use ($productOfferCollectionTransfer, $productOfferReferences) {
                $this->writeProductOfferCollectionByProductOfferReferences($productOfferCollectionTransfer, $productOfferReferences);
            });

            return;
        }

        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productOfferCollectionTransfer = $this->productOfferStorageReader
            ->getProductOfferSellableCollectionByProductOfferIds($productOfferIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferCollectionTransfer, $productOfferIds) {
            $this->writeProductOfferCollectionByProductOfferIds($productOfferCollectionTransfer, $productOfferIds);
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, static::COL_FK_PRODUCT_OFFER);

        if (!$productOfferIds) {
            return;
        }

        $productOfferCollectionTransfer = $this->productOfferStorageReader->getProductOfferSellableCollectionByProductOfferIds($productOfferIds);

        $this->writeProductOfferCollectionByProductOfferIds($productOfferCollectionTransfer, $productOfferIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     * @param array<string> $productOfferReferences
     *
     * @return void
     */
    protected function writeProductOfferCollectionByProductOfferReferences(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer,
        array $productOfferReferences
    ): void {
        $productOfferCollectionTransfer = $this->executeProductOfferStorageFilterPlugins($productOfferCollectionTransfer);

        $sellableProductOfferReferences = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $this->productOfferStorageEntityManager->saveProductOfferStorage($productOfferTransfer);
            $sellableProductOfferReferences[] = $productOfferTransfer->getProductOfferReference();
            $this->deleteProductOfferReferenceByStore($productOfferTransfer);
        }

        $this->productOfferStorageDeleter->deleteProductOfferStorageEntitiesByProductOfferReferences(
            array_diff($productOfferReferences, $sellableProductOfferReferences),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     * @param array<int> $productOfferIds
     *
     * @return void
     */
    protected function writeProductOfferCollectionByProductOfferIds(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer,
        array $productOfferIds
    ): void {
        $productOfferReferences = $this->extractProductOfferReferencesFromProductOfferCollection(
            $this->productOfferStorageReader->getProductOfferCollectionByProductOfferIds($productOfferIds),
        );

        $this->writeProductOfferCollectionByProductOfferReferences(
            $productOfferCollectionTransfer,
            $productOfferReferences,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    protected function deleteProductOfferReferenceByStore(ProductOfferTransfer $productOfferTransfer): void
    {
        $productOfferReferencesToRemoveGroupedByStoreName = $this->getProductOfferReferenceToRemoveGroupedByStoreName($productOfferTransfer);

        foreach ($productOfferReferencesToRemoveGroupedByStoreName as $storeName => $productOfferReference) {
            $this->productOfferStorageDeleter->deleteProductOfferStorageEntitiesByProductOfferReferences([$productOfferReference], $storeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string, string>
     */
    protected function getProductOfferReferenceToRemoveGroupedByStoreName(ProductOfferTransfer $productOfferTransfer): array
    {
        $productOfferReferencesToRemoveGroupedByStoreName = [];
        foreach ($this->getStoreTransfers() as $storeTransfer) {
            /** @var string $storeName */
            $storeName = $storeTransfer->getName();
            $productOfferReferencesToRemoveGroupedByStoreName[$storeName] = $productOfferTransfer->getProductOfferReferenceOrFail();
            foreach ($productOfferTransfer->getStores() as $productOfferStoreTransfer) {
                /** @var string $productOfferStoreName */
                $productOfferStoreName = $productOfferStoreTransfer->getName();
                if ($storeTransfer->getIdStore() === $productOfferStoreTransfer->getIdStore()) {
                    unset($productOfferReferencesToRemoveGroupedByStoreName[$productOfferStoreName]);
                }
            }
        }

        return $productOfferReferencesToRemoveGroupedByStoreName;
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoreTransfers(): array
    {
        if ($this->storeTransfers) {
            return $this->storeTransfers;
        }

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $this->storeTransfers[] = $storeTransfer;
        }

        return $this->storeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<string>
     */
    protected function extractProductOfferReferencesFromProductOfferCollection(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferReferences = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferReferences[] = $productOfferTransfer->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function executeProductOfferStorageFilterPlugins(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        foreach ($this->productOfferStorageFilterPlugins as $productOfferStorageFilterPlugin) {
            $productOfferCollectionTransfer = $productOfferStorageFilterPlugin->filterProductOfferStorages($productOfferCollectionTransfer);
        }

        return $productOfferCollectionTransfer;
    }
}
