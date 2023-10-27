<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer;

use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface;

class ProductOfferEventsWriter implements ProductOfferEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_STORE_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer\ProductOfferShipmentTypeStorageWriterInterface
     */
    protected ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer\ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter
     */
    public function __construct(
        ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferShipmentTypeStorageWriter = $productOfferShipmentTypeStorageWriter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByProductOfferEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->productOfferShipmentTypeStorageWriter->writeProductOfferShipmentTypeStorageCollectionByProductOfferIds(
            array_unique($productOfferIds),
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_PRODUCT_OFFER_STORE_FK_PRODUCT_OFFER,
        );

        $this->productOfferShipmentTypeStorageWriter->writeProductOfferShipmentTypeStorageCollectionByProductOfferIds(
            array_unique($productOfferIds),
        );
    }
}
