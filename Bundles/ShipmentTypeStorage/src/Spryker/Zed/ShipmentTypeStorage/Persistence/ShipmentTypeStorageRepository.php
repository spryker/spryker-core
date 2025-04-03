<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ShipmentTypeStorage\Persistence\Base\SpyShipmentTypeListStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStoragePersistenceFactory getFactory()
 */
class ShipmentTypeStorageRepository extends AbstractRepository implements ShipmentTypeStorageRepositoryInterface
{
    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @var string
     */
    protected const TABLE_NAME_SHIPMENT_TYPE_LIST_STORAGE = 'spy_shipment_type_list_storage';

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeIds = []): array
    {
        $shipmentTypeStorageQuery = $this->getFactory()->createShipmentTypeStorageQuery();

        if ($shipmentTypeIds) {
            $shipmentTypeStorageQuery->filterByFkShipmentType_In($shipmentTypeIds);
        }

        return $this->buildQueryFromCriteria($shipmentTypeStorageQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeListIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeListStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeListIds = []): array
    {
        if (!$this->isShipmentTypeListStorageTableExists()) {
            return [];
        }

        $shipmentTypeListStorageQuery = $this->getFactory()->createShipmentTypeListStorageQuery();

        if ($shipmentTypeListIds) {
            $shipmentTypeListStorageQuery->filterByIdShipmentTypeListStorage_In($shipmentTypeListIds);
        }

        return $this->buildQueryFromCriteria($shipmentTypeListStorageQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return bool
     */
    protected function isShipmentTypeListStorageTableExists(): bool
    {
        if (
            !class_exists(SpyShipmentTypeListStorageQuery::class) ||
            !$this->getFactory()->getPropelFacade()->tableExists(static::TABLE_NAME_SHIPMENT_TYPE_LIST_STORAGE)
        ) {
            return false;
        }

        return true;
    }
}
