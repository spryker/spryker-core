<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface
     */
    protected $productOfferStorageDeleter;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $storeTransfers;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface $productOfferStorageDeleter
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager,
        ProductOfferStorageDeleterInterface $productOfferStorageDeleter,
        MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
        $this->productOfferStorageDeleter = $productOfferStorageDeleter;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeByProductOfferReferenceEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);

        if (!$productOfferReferences) {
            return;
        }

        $this->writeByProductOfferReferences($productOfferReferences);
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    protected function writeByProductOfferReferences(array $productOfferReferences): void
    {
        $productOfferCriteriaFilterTransfer = $this->createProductOfferCriteriaFilterTransfer($productOfferReferences);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $this->merchantProductOfferStorageEntityManager->saveProductOfferStorage($productOfferTransfer);
            $this->deleteProductOfferReferenceByStore($productOfferTransfer);
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(array $productOfferReferences): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReferences($productOfferReferences)
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
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
            $this->productOfferStorageDeleter->deleteByProductOfferReferences([$productOfferReference], $storeName);
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
            $productOfferReferencesToRemoveGroupedByStoreName[$storeTransfer->getName()] = $productOfferTransfer->getProductOfferReference();
            foreach ($productOfferTransfer->getStores() as $productOfferStoreTransfer) {
                if ($storeTransfer->getIdStore() === $productOfferStoreTransfer->getIdStore()) {
                    unset($productOfferReferencesToRemoveGroupedByStoreName[$productOfferStoreTransfer->getName()]);
                }
            }
        }

        return $productOfferReferencesToRemoveGroupedByStoreName;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
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
}
