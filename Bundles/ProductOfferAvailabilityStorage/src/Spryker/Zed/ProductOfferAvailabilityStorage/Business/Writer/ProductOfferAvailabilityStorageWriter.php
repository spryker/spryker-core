<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer;

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
    public function writeProductOfferAvailabilityStorageCollectionByOmsProductReservationKeyEvents(array $eventTransfers): void
    {
        $omsProductReservationIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $productOfferAvailabilityRequestTransfers = $this->productOfferAvailabilityStorageRepository
            ->getProductOfferAvailabilityRequestsByOmsReservationIds($omsProductReservationIds);

        $this->writeProductOfferAvailabilityStorageForRequests($productOfferAvailabilityRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferStockKeyEvents(array $eventTransfers): void
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
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents(array $eventTransfers): void
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
            $productOfferAvailabilityStorageTransfer = $this->productOfferAvailabilityFacade->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);

            $key = $this->generateKey(
                $productOfferAvailabilityRequestTransfer->getProductOfferReference(),
                $productOfferAvailabilityRequestTransfer->getStore()->getName()
            );

            $productOfferAvailabilityStorageEntity = $this->productOfferAvailabilityStorageRepository->findProductOfferAvailabilityStorageByProductOfferReferenceAndStoreName(
                $productOfferAvailabilityRequestTransfer->getProductOfferReference(),
                $productOfferAvailabilityRequestTransfer->getStore()->getName()
            );

            ($productOfferAvailabilityStorageEntity ?: new SpyProductOfferAvailabilityStorage())
                ->setProductOfferReference($productOfferAvailabilityRequestTransfer->getProductOfferReference())
                ->setKey($key)
                ->setIsSendingToQueue($this->isSendingToQueue)
                ->setStore(
                    $productOfferAvailabilityRequestTransfer->getStore()->getName()
                )
                ->setData(
                    (new ProductOfferAvailabilityStorageTransfer())
                        ->setAvailability($productOfferAvailabilityStorageTransfer ? $productOfferAvailabilityStorageTransfer->getAvailability() : 0)->toArray()
                )
                ->save();
        }
    }

    /**
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $productOfferReference, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($productOfferReference)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
