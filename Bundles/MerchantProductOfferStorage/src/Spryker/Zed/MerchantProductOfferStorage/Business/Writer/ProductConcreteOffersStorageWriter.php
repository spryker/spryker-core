<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @uses \Spryker\Zed\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    public const STATUS_APPROVED = 'approved';

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
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface
     */
    protected $productConcreteOffersStorageDeleter;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager,
        ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter,
        MerchantProductOfferStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
        $this->productConcreteOffersStorageDeleter = $productConcreteOffersStorageDeleter;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void
    {
        $productSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_CONCRETE_SKU);

        if (!$productSkus) {
            return;
        }

        $this->writeProductConcreteProductOffersStorageCollectionByProductSkus($productSkus);
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductSkus(array $productSkus): void
    {
        $productSkus = array_unique($productSkus);
        $flippedProductSkus = array_flip($productSkus);

        $productOfferCriteriaFilterTransfer = $this->createProductOfferCriteriaFilterTransfer($productSkus);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        $productOffersGroupedBySku = $this->groupProductOfferReferencesByConcreteSku($productOfferCollectionTransfer);

        $productSkusToRemove = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $productSkusToRemove[$storeTransfer->getName()] = $productSkus;
        }

        foreach ($productOffersGroupedBySku as $sku => $productOfferReferencesGroupedByStore) {
            foreach ($productOfferReferencesGroupedByStore as $store => $productOfferReferenceList) {
                $productConcreteProductOffersStorageEntity = $this->getProductConcreteProductOffersStorageQuery()
                    ->filterByConcreteSku($sku)
                    ->filterByStore($store)
                    ->findOneOrCreate();

                $productConcreteProductOffersStorageEntity->setData($productOfferReferenceList);
                $productConcreteProductOffersStorageEntity->save();

                unset($productSkusToRemove[$store][$flippedProductSkus[$sku]]);
            }
        }

        foreach ($productSkusToRemove as $store => $productSkus) {
            $this->productConcreteOffersStorageDeleter->deleteProductConcreteProductOffersStorageByProductSkusAndStore(
                $productSkus,
                $store
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function groupProductOfferReferencesByConcreteSku(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferReferencesGroupedBySku = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!isset($productOfferReferencesGroupedBySku[$productOfferTransfer->getConcreteSku()])) {
                $productOfferReferencesGroupedBySku[$productOfferTransfer->getConcreteSku()] = [];
            }
            foreach ($productOfferTransfer->getStores() as $storeTransfer) {
                $productOfferReferencesGroupedBySku[$productOfferTransfer->getConcreteSku()][$storeTransfer->getName()][] =
                    mb_strtolower($productOfferTransfer->getProductOfferReference());
            }
        }

        return $productOfferReferencesGroupedBySku;
    }

    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(array $productSkus): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setConcreteSkus($productSkus)
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }

    /**
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery
     */
    protected function getProductConcreteProductOffersStorageQuery(): SpyProductConcreteProductOffersStorageQuery
    {
        return SpyProductConcreteProductOffersStorageQuery::create();
    }
}
