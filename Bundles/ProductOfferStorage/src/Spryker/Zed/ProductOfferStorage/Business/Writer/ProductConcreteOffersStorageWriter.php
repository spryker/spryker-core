<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface
     */
    protected $productConcreteProductOffersStorageDeleter;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface
     */
    protected $productOfferCriteriaTransferProvider;

    /**
     * @var array<string>
     */
    protected $storeNamesList;

    /**
     * @var array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface>
     */
    protected $productOfferStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface $productOfferStorageRepository
     * @param \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface $productConcreteProductOffersStorageDeleter
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferStorage\Business\Writer\ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider
     * @param array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface> $productOfferStorageFilterPlugins
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager,
        ProductOfferStorageRepositoryInterface $productOfferStorageRepository,
        ProductConcreteProductOffersStorageDeleterInterface $productConcreteProductOffersStorageDeleter,
        ProductOfferStorageToStoreFacadeInterface $storeFacade,
        ProductOfferCriteriaTransferProviderInterface $productOfferCriteriaTransferProvider,
        array $productOfferStorageFilterPlugins
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
        $this->productOfferStorageRepository = $productOfferStorageRepository;
        $this->productConcreteProductOffersStorageDeleter = $productConcreteProductOffersStorageDeleter;
        $this->storeFacade = $storeFacade;
        $this->productOfferCriteriaTransferProvider = $productOfferCriteriaTransferProvider;
        $this->productOfferStorageFilterPlugins = $productOfferStorageFilterPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void
    {
        /**
         * @var array<string> $productSkus
         */
        $productSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_CONCRETE_SKU);

        $this->getTransactionHandler()->handleTransaction(function () use ($productSkus) {
            $this->writeCollectionByProductSkus($productSkus);
        });
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return void
     */
    protected function writeCollectionByProductSkus(array $productConcreteSkus): void
    {
        if (count($productConcreteSkus) === 0) {
            return;
        }

        $productConcreteSkus = array_unique($productConcreteSkus);

        $productOfferCriteriaTransfer = $this->productOfferCriteriaTransferProvider->createSellableProductOfferCriteriaTransfer()
            ->setConcreteSkus($productConcreteSkus);
        $productOfferCollectionTransfer = $this->productOfferStorageRepository->getProductOffers($productOfferCriteriaTransfer);
        $productOfferCollectionTransfer = $this->executeProductOfferStorageFilterPlugins($productOfferCollectionTransfer);

        $productOfferReferencesGroupedByConcreteSku = $this->getProductOfferReferencesGroupedByConcreteSku(
            $productConcreteSkus,
            $productOfferCollectionTransfer,
        );

        foreach ($productOfferReferencesGroupedByConcreteSku as $concreteSku => $productOfferReferencesGroupedByStore) {
            $storeNamesToRemove = [];

            foreach ($productOfferReferencesGroupedByStore as $storeName => $productOfferReferenceList) {
                if (!$productOfferReferenceList) {
                    $storeNamesToRemove[] = $storeName;

                    continue;
                }
                $this->productOfferStorageEntityManager->saveProductConcreteProductOffers($concreteSku, $productOfferReferenceList, $storeName);
            }

            if ($storeNamesToRemove) {
                $this->deleteProductConcreteProductOffers($storeNamesToRemove, $concreteSku);
            }
        }
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<mixed>
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
            /** @var string $sku */
            $sku = $productOfferTransfer->getConcreteSku();
            if (!isset($productOfferReferencesGroupedByConcreteSku[$sku])) {
                $productOfferReferencesGroupedByConcreteSku[$sku] = [];
            }
            foreach ($productOfferTransfer->getStores() as $storeTransfer) {
                /** @var string $productOfferReference */
                $productOfferReference = $productOfferTransfer->getProductOfferReference();
                /** @var string $storeName */
                $storeName = $storeTransfer->getName();
                $productOfferReferencesGroupedByConcreteSku[$sku][$storeName][] =
                    mb_strtolower($productOfferReference);
            }
        }

        return $productOfferReferencesGroupedByConcreteSku;
    }

    /**
     * @param array<string> $storeNamesToRemove
     * @param string $productSku
     *
     * @return void
     */
    protected function deleteProductConcreteProductOffers(array $storeNamesToRemove, string $productSku): void
    {
        foreach ($storeNamesToRemove as $storeName) {
            $this->productConcreteProductOffersStorageDeleter->deleteProductConcreteProductOffersStorageEntitiesByProductSkus(
                [$productSku],
                $storeName,
            );
        }
    }

    /**
     * @return array<string>
     */
    protected function getStoreNamesList(): array
    {
        if ($this->storeNamesList) {
            return $this->storeNamesList;
        }

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            /** @var string $name */
            $name = $storeTransfer->getName();
            $this->storeNamesList[] = $name;
        }

        return $this->storeNamesList;
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
