<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface;
use Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    use TransactionTrait;

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_CONCRETE_SKU
     *
     * @var string
     */
    protected const COL_CONCRETE_SKU = 'spy_product_offer.concrete_sku';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface
     */
    protected $productOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface
     */
    protected $productConcreteProductOffersStorageDeleter;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @var array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface>
     */
    protected $productOfferStorageFilterPlugins;

    /**
     * @var array<string>
     */
    protected $storeNamesList;

    /**
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager
     * @param \Spryker\Zed\ProductOfferStorage\Business\Deleter\ProductConcreteProductOffersStorageDeleterInterface $productConcreteProductOffersStorageDeleter
     * @param \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferStorage\Business\Reader\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface> $productOfferStorageFilterPlugins
     */
    public function __construct(
        ProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferStorageEntityManagerInterface $productOfferStorageEntityManager,
        ProductConcreteProductOffersStorageDeleterInterface $productConcreteProductOffersStorageDeleter,
        ProductOfferStorageToStoreFacadeInterface $storeFacade,
        ProductOfferStorageReaderInterface $productOfferStorageReader,
        array $productOfferStorageFilterPlugins
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferStorageEntityManager = $productOfferStorageEntityManager;
        $this->productConcreteProductOffersStorageDeleter = $productConcreteProductOffersStorageDeleter;
        $this->storeFacade = $storeFacade;
        $this->productOfferStorageReader = $productOfferStorageReader;
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
         * @var array<string> $productConcreteSkus
         */
        $productConcreteSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, static::COL_CONCRETE_SKU);

        if (!$productConcreteSkus) {
            return;
        }

        $productOfferCollectionTransfer = $this->productOfferStorageReader->getProductOfferCollectionByProductConcreteSkus($productConcreteSkus);

        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferCollectionTransfer, $productConcreteSkus) {
            $this->writeCollectionByProductSkus($productOfferCollectionTransfer, $productConcreteSkus);
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_FK_PRODUCT_OFFER,
        );

        if (!$productOfferIds) {
            return;
        }

        $productOfferCollectionTransfer = $this->productOfferStorageReader->getProductOfferCollectionByProductOfferIds($productOfferIds);
        $productConcreteSkus = $this->extractProductConcreteSkusFromProductOfferCollection($productOfferCollectionTransfer);

        if (!$productConcreteSkus) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($productOfferCollectionTransfer, $productConcreteSkus) {
            $this->writeCollectionByProductSkus($productOfferCollectionTransfer, $productConcreteSkus);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     * @param array<string> $productConcreteSkus
     *
     * @return void
     */
    protected function writeCollectionByProductSkus(ProductOfferCollectionTransfer $productOfferCollectionTransfer, array $productConcreteSkus): void
    {
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
                $productOfferReferencesGroupedByConcreteSku[$sku][$storeName][] = mb_strtolower($productOfferReference);
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
     * @return array<string>
     */
    protected function extractProductConcreteSkusFromProductOfferCollection(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productConcreteSkus = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productConcreteSkus[] = $productOfferTransfer->getConcreteSkuOrFail();
        }

        return $productConcreteSkus;
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
