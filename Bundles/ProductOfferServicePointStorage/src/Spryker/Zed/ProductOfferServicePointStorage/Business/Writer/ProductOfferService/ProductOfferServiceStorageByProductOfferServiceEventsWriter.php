<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferService;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;

class ProductOfferServiceStorageByProductOfferServiceEventsWriter implements ProductOfferServiceStorageByProductOfferServiceEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_service.fk_product_offer';

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    protected ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface
     */
    protected ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
     */
    public function __construct(
        ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade,
        ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
        $this->productOfferServiceStorageWriter = $productOfferServiceStorageWriter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServiceEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_PRODUCT_OFFER,
        );

        $this->productOfferServiceStorageWriter->writeProductOfferServiceStorageCollection($productOfferIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServicePublishEvents(array $eventEntityTransfers): void
    {
        $productOfferServiceIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $productOfferServiceCollectionTransfer = $this->getProductOfferServiceCollection($productOfferServiceIds);

        $this->productOfferServiceStorageWriter->writeProductOfferServiceStorageCollection(
            $this->extractProductOfferIdsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer),
        );
    }

    /**
     * @param list<int> $productOfferServiceIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    protected function getProductOfferServiceCollection(array $productOfferServiceIds): ProductOfferServiceCollectionTransfer
    {
        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setProductOfferServiceConditions(
            (new ProductOfferServiceConditionsTransfer())
                ->setProductOfferServiceIds($productOfferServiceIds)
                ->setGroupByIdProductOffer(true),
        );

        return $this->productOfferServicePointFacade->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
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
