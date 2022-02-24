<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageBusinessFactory getFactory()
 */
class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface
     */
    protected $productOfferStorageRepository;

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
     * @var \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface
     */
    protected $productOfferCriteriaTransferProvider;

    /**
     * @var array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface>
     */
    protected $productOfferStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface $productOfferStorageRepository
     * @param \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface $productOfferStorageDeleter
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider
     * @param array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface> $productOfferStorageFilterPlugins
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager,
        ProductOfferStorageRepositoryInterface $productOfferStorageRepository,
        ProductOfferStorageDeleterInterface $productOfferStorageDeleter,
        ProductOfferStorageToStoreFacadeInterface $storeFacade,
        ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider,
        array $productOfferStorageFilterPlugins
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
        $this->productOfferStorageRepository = $productOfferStorageRepository;
        $this->productOfferStorageDeleter = $productOfferStorageDeleter;
        $this->storeFacade = $storeFacade;
        $this->productOfferCriteriaTransferProvider = $productOfferCriteriaTransferProvider;
        $this->productOfferStorageFilterPlugins = $productOfferStorageFilterPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);

        if (!$productOfferReferences) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferReferences) {
            $this->writeByProductOfferReferences($productOfferReferences);
        });
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return void
     */
    protected function writeByProductOfferReferences(array $productOfferReferences): void
    {
        $sellableProductOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider->createSellableProductOfferCriteriaTransfer()
            ->setProductOfferReferences($productOfferReferences);

        $productOfferCollectionTransfer = $this->productOfferStorageRepository
            ->getProductOffers($sellableProductOfferCriteriaTransfer);
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
     * @return array
     */
    protected function getProductOfferReferenceToRemoveGroupedByStoreName(ProductOfferTransfer $productOfferTransfer): array
    {
        $productOfferReferencesToRemoveGroupedByStoreName = [];
        foreach ($this->getStoreTransfers() as $storeTransfer) {
            /** @var string $storeName */
            $storeName = $storeTransfer->getName();
            $productOfferReferencesToRemoveGroupedByStoreName[$storeName] = $productOfferTransfer->getProductOfferReference();
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
