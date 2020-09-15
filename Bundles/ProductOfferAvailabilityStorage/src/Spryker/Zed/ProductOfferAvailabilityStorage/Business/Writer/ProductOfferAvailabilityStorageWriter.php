<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface;

class ProductOfferAvailabilityStorageWriter implements ProductOfferAvailabilityStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface
     */
    protected $productOfferAvailabilityFacade;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface
     */
    protected $productOfferAvailabilityStorageRepository;

    /**
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface $productOfferAvailabilityStorageRepository
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductOfferAvailabilityStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade,
        ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService,
        ProductOfferAvailabilityStorageRepositoryInterface $productOfferAvailabilityStorageRepository,
        bool $isSendingToQueue
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferAvailabilityFacade = $productOfferAvailabilityFacade;
        $this->synchronizationService = $synchronizationService;
        $this->productOfferAvailabilityStorageRepository = $productOfferAvailabilityStorageRepository;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
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
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[] $productOfferAvailabilityRequestTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageForRequests(array $productOfferAvailabilityRequestTransfers): void
    {
        foreach ($productOfferAvailabilityRequestTransfers as $productOfferAvailabilityRequestTransfer) {
            $productOfferAvailabilityTransfer = $this->productOfferAvailabilityFacade->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);
            $productOfferAvailabilityStorageEntity = $this->productOfferAvailabilityStorageRepository->findProductOfferAvailabilityStorageByProductOfferReferenceAndStoreName(
                $productOfferAvailabilityRequestTransfer->getProductOfferReference(),
                $productOfferAvailabilityRequestTransfer->getStore()->getName()
            );

            if (!$productOfferAvailabilityTransfer && $productOfferAvailabilityStorageEntity) {
                $productOfferAvailabilityStorageEntity->delete();

                return;
            }

            if (!$productOfferAvailabilityStorageEntity) {
                $productOfferAvailabilityStorageEntity = new SpyProductOfferAvailabilityStorage();
            }

            $productOfferAvailabilityStorageTransfer = $this->mapProductConcreteAvailabilityTransferToProductOfferAvailabilityStorageTransfer(
                $productOfferAvailabilityTransfer,
                new ProductOfferAvailabilityStorageTransfer()
            );

            $productOfferAvailabilityStorageTransfer->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference());

            $productOfferAvailabilityStorageEntity
                ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
                ->setKey($this->generateKey($productOfferAvailabilityRequestTransfer))
                ->setIsSendingToQueue($this->isSendingToQueue)
                ->setStore(
                    $productOfferAvailabilityRequestTransfer->getStore()->getName()
                )
                ->setData($productOfferAvailabilityStorageTransfer->toArray())
                ->save();
        }
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
            ->setStore($productOfferAvailabilityRequestTransfer->getStore()->getName());

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
