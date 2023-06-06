<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface getEntityManager()
 */
class ShipmentTypeStorageFacade extends AbstractFacade implements ShipmentTypeStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createShipmentTypeStorageWriter()
            ->writeShipmentTypeStorageCollectionByShipmentTypeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createShipmentTypeStorageWriter()
            ->writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeIds = []): array
    {
        return $this->getRepository()
            ->getShipmentTypeStorageSynchronizationDataTransfers($filterTransfer, $shipmentTypeIds);
    }
}
