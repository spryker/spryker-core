<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Persistence;

use Orm\Zed\ShipmentTypeServicePoint\Persistence\Map\SpyShipmentTypeServiceTypeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointPersistenceFactory getFactory()
 */
class ShipmentTypeServicePointRepository extends AbstractRepository implements ShipmentTypeServicePointRepositoryInterface
{
    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return array<int, int>
     */
    public function getServiceTypeIdsIndexedByIdShipmentType(array $shipmentTypeIds): array
    {
        return $this->getFactory()
            ->createShipmentTypeServiceTypeQuery()
            ->select([
                SpyShipmentTypeServiceTypeTableMap::COL_FK_SHIPMENT_TYPE,
                SpyShipmentTypeServiceTypeTableMap::COL_FK_SERVICE_TYPE,
            ])
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->find()
            ->toKeyValue(SpyShipmentTypeServiceTypeTableMap::COL_FK_SHIPMENT_TYPE, SpyShipmentTypeServiceTypeTableMap::COL_FK_SERVICE_TYPE);
    }
}
