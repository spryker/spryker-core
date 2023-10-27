<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer;

use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface;

class ProductOfferShipmentTypeEventsWriter implements ProductOfferShipmentTypeEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER = 'spy_product_offer_shipment_type.fk_product_offer';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface
     */
    protected ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer\ProductOfferShipmentTypeStorageWriterInterface
     */
    protected ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer\ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter
     */
    public function __construct(
        ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader,
        ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductOfferShipmentTypeStorageWriterInterface $productOfferShipmentTypeStorageWriter
    ) {
        $this->productOfferShipmentTypeReader = $productOfferShipmentTypeReader;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferShipmentTypeStorageWriter = $productOfferShipmentTypeStorageWriter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByProductOfferShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $productOfferIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER,
        );

        if ($productOfferIds !== []) {
            $this->productOfferShipmentTypeStorageWriter->writeProductOfferShipmentTypeStorageCollectionByProductOfferIds(
                array_unique($productOfferIds),
            );

            return;
        }

        $productOfferShipmentTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCollectionByProductOfferShipmentTypeIds($productOfferShipmentTypeIds);
    }

    /**
     * @param list<int> $productOfferShipmentTypeIds
     *
     * @return void
     */
    protected function writeCollectionByProductOfferShipmentTypeIds(array $productOfferShipmentTypeIds): void
    {
        $productOfferShipmentTypeIterator = $this->productOfferShipmentTypeReader
            ->getProductOfferShipmentTypeIteratorByProductOfferShipmentTypeIds($productOfferShipmentTypeIds);

        foreach ($productOfferShipmentTypeIterator as $productOfferShipmentTypeTransfers) {
            $this->productOfferShipmentTypeStorageWriter->writeProductOfferShipmentTypeStorageCollection($productOfferShipmentTypeTransfers);
        }
    }
}
