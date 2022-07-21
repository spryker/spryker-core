<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface;

class ProductOfferStorageReader implements ProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface
     */
    protected $productOfferStorageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface
     */
    protected $productOfferCriteriaTransferProvider;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface $productOfferStorageRepository
     * @param \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider
     */
    public function __construct(
        ProductOfferStorageRepositoryInterface $productOfferStorageRepository,
        ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider
    ) {
        $this->productOfferStorageRepository = $productOfferStorageRepository;
        $this->productOfferCriteriaTransferProvider = $productOfferCriteriaTransferProvider;
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferIds(array $productOfferIds): ProductOfferCollectionTransfer
    {
        $productOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider
            ->createSellableProductOfferCriteriaTransfer()
            ->setProductOfferIds($productOfferIds);

        return $this->productOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferCollectionTransfer
    {
        $productOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider
            ->createSellableProductOfferCriteriaTransfer()
            ->setProductOfferReferences($productOfferReferences);

        return $this->productOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductConcreteSkus(array $productConcreteSkus): ProductOfferCollectionTransfer
    {
        $productConcreteSkus = array_unique($productConcreteSkus);

        $productOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider
            ->createSellableProductOfferCriteriaTransfer()
            ->setConcreteSkus($productConcreteSkus);

        return $this->productOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);
    }

    /**
     * @param array<int> $productOfferIds
     * @param array<int> $storeIds
     *
     * @return array<string, array<string>>
     */
    public function getProductOfferReferencesGroupedByStore(array $productOfferIds, array $storeIds): array
    {
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferIds($productOfferIds)
            ->setStoreIds($storeIds);
        $productOfferCollectionTransfer = $this->productOfferStorageRepository
            ->getProductOffers($productOfferCriteriaTransfer);

        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        $productOfferReferencesGroupedByStore = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $storeTransfers = $productOfferTransfer->getStores();
            foreach ($storeTransfers as $storeTransfer) {
                $productOfferReferencesGroupedByStore[$storeTransfer->getNameOrFail()][] = $productOfferTransfer->getProductOfferReferenceOrFail();
            }
        }

        return $productOfferReferencesGroupedByStore;
    }
}
