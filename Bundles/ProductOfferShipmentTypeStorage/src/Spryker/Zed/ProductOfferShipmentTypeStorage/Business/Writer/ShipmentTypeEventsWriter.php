<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer;

use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader\ProductOfferShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface;

class ShipmentTypeEventsWriter implements ShipmentTypeEventsWriterInterface
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeStoreTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_type_store.fk_shipment_type';

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
    public function writeProductOfferShipmentTypeStorageCollectionByShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCollectionByShipmentTypeIds($shipmentTypeIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, static::COL_FK_SHIPMENT_TYPE);

        $this->writeCollectionByShipmentTypeIds($shipmentTypeIds);
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    protected function writeCollectionByShipmentTypeIds(array $shipmentTypeIds): void
    {
        $productOfferShipmentTypeTransfersIterator = $this->productOfferShipmentTypeReader->getProductOfferShipmentTypeIteratorByShipmentTypeIds(
            $shipmentTypeIds,
        );

        foreach ($productOfferShipmentTypeTransfersIterator as $productOfferShipmentTypeTransfers) {
            $this->productOfferShipmentTypeStorageWriter->writeProductOfferShipmentTypeStorageCollection(
                $productOfferShipmentTypeTransfers,
            );
        }
    }
}
