<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
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
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToProductOfferFacadeInterface
     */
    protected ProductOfferStorageToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface
     */
    protected ProductOfferStorageToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface $productOfferStorageRepository
     * @param \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductOfferStorageRepositoryInterface $productOfferStorageRepository,
        ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider,
        ProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        ProductOfferStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->productOfferStorageRepository = $productOfferStorageRepository;
        $this->productOfferCriteriaTransferProvider = $productOfferCriteriaTransferProvider;
        $this->productOfferFacade = $productOfferFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferSellableCollectionByProductOfferIds(array $productOfferIds): ProductOfferCollectionTransfer
    {
        $productOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider
            ->createSellableProductOfferCriteriaTransfer()
            ->setProductOfferIds($productOfferIds);

        return $this->productOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferIds(array $productOfferIds): ProductOfferCollectionTransfer
    {
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())->setProductOfferIds($productOfferIds);

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
            ->setProductOfferConditions((new ProductOfferConditionsTransfer())->setProductOfferIds($productOfferIds));
        $productOfferTransfers = $this->productOfferFacade
            ->getProductOfferCollection($productOfferCriteriaTransfer)
            ->getProductOffers();

        $storeNamesIndexedByIdStore = $this->getStoreNamesIndexedByIdStore($this->storeFacade->getAllStores());

        $productOfferReferencesGroupedByStore = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            foreach ($storeIds as $idStore) {
                if (!isset($storeNamesIndexedByIdStore[$idStore])) {
                    continue;
                }

                $productOfferReferencesGroupedByStore[$storeNamesIndexedByIdStore[$idStore]][] = $productOfferTransfer->getProductOfferReferenceOrFail();
            }
        }

        return $productOfferReferencesGroupedByStore;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<int, string>
     */
    protected function getStoreNamesIndexedByIdStore(array $storeTransfers): array
    {
        $storeNamesIndexedByIdStore = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeNamesIndexedByIdStore[$storeTransfer->getIdStoreOrFail()] = $storeTransfer->getNameOrFail();
        }

        return $storeNamesIndexedByIdStore;
    }
}
