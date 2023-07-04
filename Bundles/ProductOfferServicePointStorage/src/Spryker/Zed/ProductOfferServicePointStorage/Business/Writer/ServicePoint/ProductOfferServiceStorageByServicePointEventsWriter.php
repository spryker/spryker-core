<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ServicePoint;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig;

class ProductOfferServiceStorageByServicePointEventsWriter implements ProductOfferServiceStorageByServicePointEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface
     */
    protected ProductOfferServicePointStorageToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    protected ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig
     */
    protected ProductOfferServicePointStorageConfig $productOfferServicePointStorageConfig;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface
     */
    protected ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig $productOfferServicePointStorageConfig
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
     */
    public function __construct(
        ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferServicePointStorageToServicePointFacadeInterface $servicePointFacade,
        ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade,
        ProductOfferServicePointStorageConfig $productOfferServicePointStorageConfig,
        ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->servicePointFacade = $servicePointFacade;
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
        $this->productOfferServicePointStorageConfig = $productOfferServicePointStorageConfig;
        $this->productOfferServiceStorageWriter = $productOfferServiceStorageWriter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServiceEvents(array $eventEntityTransfers): void
    {
        $serviceIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeProductOfferServiceStorageCollectionByServiceIds($serviceIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeProductOfferServiceStorageCollectionByServiceIds(
            $this->getServiceIdsByServicePointIds($servicePointIds),
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SERVICE_POINT,
        );

        $this->writeProductOfferServiceStorageCollectionByServiceIds(
            $this->getServiceIdsByServicePointIds($servicePointIds),
        );
    }

    /**
     * @param list<int> $serviceIds
     *
     * @return void
     */
    protected function writeProductOfferServiceStorageCollectionByServiceIds(array $serviceIds): void
    {
        /** @var list<int> $serviceIds */
        $serviceIds = array_filter($serviceIds);
        if (!$serviceIds) {
            return;
        }

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setProductOfferServiceConditions(
            (new ProductOfferServiceConditionsTransfer())
                ->setServiceIds($serviceIds)
                ->setGroupByIdProductOffer(true),
        );

        $readCollectionBatchSize = $this->productOfferServicePointStorageConfig->getReadCollectionBatchSize();
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($readCollectionBatchSize);
            $productOfferServiceCriteriaTransfer->setPagination($paginationTransfer);

            $productOfferServiceCollectionTransfer = $this->productOfferServicePointFacade->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
            if (!count($productOfferServiceCollectionTransfer->getProductOfferServices())) {
                break;
            }

            $this->productOfferServiceStorageWriter->writeProductOfferServiceStorageCollection(
                $this->extractProductOfferIdsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer),
            );

            $offset += $readCollectionBatchSize;
        } while (
            count($productOfferServiceCollectionTransfer->getProductOfferServices()) !== 0
        );
    }

    /**
     * @param list<int> $servicePointIds
     *
     * @return list<int>
     */
    protected function getServiceIdsByServicePointIds(array $servicePointIds): array
    {
        /** @var list<int> $servicePointIds */
        $servicePointIds = array_filter($servicePointIds);
        if (!$servicePointIds) {
            return [];
        }

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions(
            (new ServiceConditionsTransfer())->setServicePointIds($servicePointIds),
        );

        $serviceCollectionTransfer = $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);

        return $this->extractServiceIdsFromServiceCollectionTransfer($serviceCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractServiceIdsFromServiceCollectionTransfer(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $serviceIds = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceIds[] = $serviceTransfer->getIdServiceOrFail();
        }

        return $serviceIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractProductOfferIdsFromProductOfferServiceCollectionTransfer(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): array {
        $productOfferIds = [];
        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $productOfferIds[] = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
        }

        return $productOfferIds;
    }
}
