<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @var array
     */
    public static $storeNames = [];

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface
     */
    protected $merchantProductOfferStorageRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface
     */
    protected $productConcreteOffersStorageDeleter;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var string[]
     */
    protected $storeNamesList;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager,
        MerchantProductOfferStorageRepositoryInterface $merchantProductOfferStorageRepository,
        ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter,
        MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
        $this->merchantProductOfferStorageRepository = $merchantProductOfferStorageRepository;
        $this->productConcreteOffersStorageDeleter = $productConcreteOffersStorageDeleter;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$merchantIds) {
            return;
        }
        $productSkus = $this->merchantProductOfferStorageRepository->getProductConcreteSkusByMerchantIds($merchantIds);

        $this->writeCollectionByProductSkus($productSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductSkuEvents(array $eventTransfers): void
    {
        /**
         * @var string[] $productSkus
         */
        $productSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_CONCRETE_SKU);

        if (!$productSkus) {
            return;
        }

        $this->writeCollectionByProductSkus($productSkus);
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    protected function writeCollectionByProductSkus(array $productConcreteSkus): void
    {
        $productConcreteSkus = array_unique($productConcreteSkus);

        $productOfferCriteriaTransfer = $this->createProductOfferCriteriaTransfer($productConcreteSkus);
        $productOfferCollectionTransfer = $this->merchantProductOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);

        $productOfferReferencesGroupedByConcreteSku = $this->getProductOfferReferencesGroupedByConcreteSku(
            $productConcreteSkus,
            $productOfferCollectionTransfer
        );

        foreach ($productOfferReferencesGroupedByConcreteSku as $concreteSku => $productOfferReferencesGroupedByStore) {
            $storeNamesToRemove = [];

            foreach ($productOfferReferencesGroupedByStore as $storeName => $productOfferReferenceList) {
                if (!$productOfferReferenceList) {
                    $storeNamesToRemove[] = $storeName;

                    continue;
                }
                $this->merchantProductOfferStorageEntityManager->saveProductConcreteProductOffers($concreteSku, $productOfferReferenceList, $storeName);
            }

            if ($storeNamesToRemove) {
                $this->deleteProductConcreteProductOffers($storeNamesToRemove, $concreteSku);
            }
        }
    }

    /**
     * @param string[] $productConcreteSkus
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function getProductOfferReferencesGroupedByConcreteSku(
        array $productConcreteSkus,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): array {
        $productOfferReferencesGroupedByConcreteSku = [];
        foreach ($productConcreteSkus as $productConcreteSku) {
            foreach ($this->getStoreNamesList() as $storeName) {
                $productOfferReferencesGroupedByConcreteSku[$productConcreteSku][$storeName] = [];
            }
        }

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!isset($productOfferReferencesGroupedByConcreteSku[$productOfferTransfer->getConcreteSku()])) {
                $productOfferReferencesGroupedByConcreteSku[$productOfferTransfer->getConcreteSku()] = [];
            }
            foreach ($productOfferTransfer->getStores() as $storeTransfer) {
                $productOfferReferencesGroupedByConcreteSku[$productOfferTransfer->getConcreteSku()][$storeTransfer->getName()][] =
                    mb_strtolower($productOfferTransfer->getProductOfferReference());
            }
        }

        return $productOfferReferencesGroupedByConcreteSku;
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    protected function createProductOfferCriteriaTransfer(array $productConcreteSkus): ProductOfferCriteriaTransfer
    {
        return (new ProductOfferCriteriaTransfer())
            ->setConcreteSkus($productConcreteSkus)
            ->setIsActive(true)
            ->setIsActiveMerchant(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }

    /**
     * @param string[] $storeNamesToRemove
     * @param string $productSku
     *
     * @return void
     */
    protected function deleteProductConcreteProductOffers(array $storeNamesToRemove, string $productSku): void
    {
        foreach ($storeNamesToRemove as $storeName) {
            $this->productConcreteOffersStorageDeleter->deleteCollectionByProductSkus(
                [$productSku],
                $storeName
            );
        }
    }

    /**
     * @return string[]
     */
    protected function getStoreNamesList(): array
    {
        if ($this->storeNamesList) {
            return $this->storeNamesList;
        }

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $this->storeNamesList[] = $storeTransfer->getName();
        }

        return $this->storeNamesList;
    }
}
