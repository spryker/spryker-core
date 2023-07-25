<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilderInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilterInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface;

class ProductOfferAvailabilityStorageWriter implements ProductOfferAvailabilityStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @uses \Orm\Zed\Stock\Persistence\Map\SpyStockStoreTableMap::COL_FK_STOCK
     *
     * @var string
     */
    protected const COL_FK_STOCK = 'spy_stock_store.fk_stock';

    /**
     * @uses \Orm\Zed\Stock\Persistence\Map\SpyStockStoreTableMap::COL_FK_STORE
     *
     * @var string
     */
    protected const COL_FK_STORE = 'spy_stock_store.fk_store';

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface
     */
    protected ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    protected ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface
     */
    protected ProductOfferAvailabilityStorageRepositoryInterface $productOfferAvailabilityStorageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilderInterface
     */
    protected ProductOfferAvailabilityRequestBuilderInterface $productOfferAvailabilityRequestBuilder;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilterInterface
     */
    protected ProductOfferAvailabilityRequestFilterInterface $productOfferAvailabilityRequestFilter;

    /**
     * @var bool
     */
    protected bool $isSendingToQueue;

    /**
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface $productOfferAvailabilityStorageRepository
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder\ProductOfferAvailabilityRequestBuilderInterface $productOfferAvailabilityRequestBuilder
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter\ProductOfferAvailabilityRequestFilterInterface $productOfferAvailabilityRequestFilter
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade,
        ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService,
        ProductOfferAvailabilityStorageRepositoryInterface $productOfferAvailabilityStorageRepository,
        ProductOfferAvailabilityRequestBuilderInterface $productOfferAvailabilityRequestBuilder,
        ProductOfferAvailabilityRequestFilterInterface $productOfferAvailabilityRequestFilter,
        bool $isSendingToQueue
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferAvailabilityFacade = $productOfferAvailabilityFacade;
        $this->synchronizationService = $synchronizationService;
        $this->productOfferAvailabilityStorageRepository = $productOfferAvailabilityStorageRepository;
        $this->productOfferAvailabilityRequestBuilder = $productOfferAvailabilityRequestBuilder;
        $this->productOfferAvailabilityRequestFilter = $productOfferAvailabilityRequestFilter;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByOmsProductOfferReservationIdEvents(array $eventTransfers): void
    {
        $omsProductOfferReservationIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository
            ->getProductOfferAvailabilityRequestsByOmsProductOfferReservationIds($omsProductOfferReservationIds);

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStockStoreEvents(array $eventTransfers): void
    {
        $storeIdsGroupedByIdStock = $this->getStoreIdsGroupedByIdStock($eventTransfers);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository->getProductOfferAvailabilityRequestsByStockIds(
            array_keys($storeIdsGroupedByIdStock),
        );

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityRequestBuilder->buildProductOfferAvailabilityRequestsWithStore(
            $productOfferAvailabilityRequestTransfers,
            $storeIdsGroupedByIdStock,
        );

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStockEvents(array $eventTransfers): void
    {
        $stockIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository->getProductOfferAvailabilityRequestsByStockIds($stockIds);
        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityRequestFilter->filterOutProductOfferAvailabilityRequestTransfersWithoutStores(
            $productOfferAvailabilityRequestTransfers,
        );

        if (!count($productOfferAvailabilityRequestTransfers)) {
            return;
        }

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStockIdEvents(array $eventTransfers): void
    {
        $productOfferStockIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository
            ->getProductOfferAvailabilityRequestsByProductOfferStockIds($productOfferStockIds);

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, static::COL_FK_PRODUCT_OFFER);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository
            ->getProductOfferAvailabilityRequestsByProductOfferIds($productOfferIds);

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferIdEvents(array $eventTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository
            ->getProductOfferAvailabilityRequestsByProductOfferIds($productOfferIds);

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer> $productOfferAvailabilityRequestTransfers
     *
     * @return void
     */
    protected function writeProductOfferAvailabilityStorageForRequests(array $productOfferAvailabilityRequestTransfers): void
    {
        foreach ($productOfferAvailabilityRequestTransfers as $productOfferAvailabilityRequestTransfer) {
            $productOfferAvailabilityTransfer = $this->productOfferAvailabilityFacade->findProductConcreteAvailability($productOfferAvailabilityRequestTransfer);
            $productOfferAvailabilityStorageEntity = $this->productOfferAvailabilityStorageRepository->findProductOfferAvailabilityStorageByProductOfferReferenceAndStoreName(
                $productOfferAvailabilityRequestTransfer->getProductOfferReferenceOrFail(),
                $productOfferAvailabilityRequestTransfer->getStoreOrFail()->getNameOrFail(),
            );

            if (!$productOfferAvailabilityTransfer && $productOfferAvailabilityStorageEntity) {
                $productOfferAvailabilityStorageEntity->delete();

                return;
            }

            if (!$productOfferAvailabilityStorageEntity) {
                $productOfferAvailabilityStorageEntity = new SpyProductOfferAvailabilityStorage();
            }

            /** @var \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer */
            $productOfferAvailabilityStorageTransfer = $this->mapProductConcreteAvailabilityTransferToProductOfferAvailabilityStorageTransfer(
                $productOfferAvailabilityTransfer,
                new ProductOfferAvailabilityStorageTransfer(),
            );

            $productOfferAvailabilityStorageTransfer->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference());

            $productOfferAvailabilityStorageEntity
                ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReferenceOrFail())
                ->setKey($this->generateKey($productOfferAvailabilityRequestTransfer))
                ->setIsSendingToQueue($this->isSendingToQueue)
                ->setStore(
                    $productOfferAvailabilityRequestTransfer->getStoreOrFail()->getNameOrFail(),
                )
                ->setData($productOfferAvailabilityStorageTransfer->toArray())
                ->save();
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return array<int, list<int>>
     */
    protected function getStoreIdsGroupedByIdStock(array $eventTransfers): array
    {
        $storeIdsGroupedByIdStock = [];
        foreach ($eventTransfers as $eventTransfer) {
            $idStore = $this->eventBehaviorFacade->getEventTransferForeignKeys([$eventTransfer], static::COL_FK_STORE)[0];
            /** @var int $idStock */
            $idStock = $this->eventBehaviorFacade->getEventTransferForeignKeys([$eventTransfer], static::COL_FK_STOCK)[0];
            $storeIdsGroupedByIdStock[$idStock][] = $idStore;
        }

        return $storeIdsGroupedByIdStock;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer
     */
    protected function mapProductConcreteAvailabilityTransferToProductOfferAvailabilityStorageTransfer(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
    ): ProductOfferAvailabilityStorageTransfer {
        $productOfferAvailabilityStorageTransfer->setAvailability($productConcreteAvailabilityTransfer->getAvailability())
            ->setIsNeverOutOfStock($productConcreteAvailabilityTransfer->getIsNeverOutOfStock());

        return $productOfferAvailabilityStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return string
     */
    protected function generateKey(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
            ->setStore($productOfferAvailabilityRequestTransfer->getStoreOrFail()->getNameOrFail());

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
