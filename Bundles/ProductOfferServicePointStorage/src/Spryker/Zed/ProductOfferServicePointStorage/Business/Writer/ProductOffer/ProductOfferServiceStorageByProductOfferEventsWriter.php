<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOffer;

use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface;

class ProductOfferServiceStorageByProductOfferEventsWriter implements ProductOfferServiceStorageByProductOfferEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_SERVICE_POINT = 'spy_product_offer_store.fk_product_offer';

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface
     */
    protected ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
     */
    public function __construct(
        ProductOfferServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferServiceStorageWriterInterface $productOfferServiceStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferServiceStorageWriter = $productOfferServiceStorageWriter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->productOfferServiceStorageWriter->writeProductOfferServiceStorageCollection($productOfferIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SERVICE_POINT,
        );

        $this->productOfferServiceStorageWriter->writeProductOfferServiceStorageCollection($productOfferIds);
    }
}
