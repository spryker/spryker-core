<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStoragePersistenceFactory getFactory()
 */
class ShipmentTypeStorageRepository extends AbstractRepository implements ShipmentTypeStorageRepositoryInterface
{
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
}
