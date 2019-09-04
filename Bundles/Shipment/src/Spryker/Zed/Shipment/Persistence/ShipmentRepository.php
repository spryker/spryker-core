<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use ArrayObject;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends AbstractRepository implements ShipmentRepositoryInterface
{
    /**
     * @param int $idShipmentMethod
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getShipmentMethodPricesByIdShipmentMethod(int $idShipmentMethod): ArrayObject
    {
        $shipmentMethodPriceEntities = $this->getShipmentMethodPriceEntitiesByIdShipmentMethod($idShipmentMethod);

        return $this->getFactory()
            ->createShipmentMethodPricesMapper()
            ->mapShipmentMethodPriceEntitiesToMoneyValueTransfers($shipmentMethodPriceEntities);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice[]
     */
    protected function getShipmentMethodPriceEntitiesByIdShipmentMethod(int $idShipmentMethod): array
    {
        return $this->queryMethodPricesByIdShipmentMethod($idShipmentMethod)
            ->find()
            ->getData();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    protected function queryMethodPricesByIdShipmentMethod(int $idShipmentMethod): SpyShipmentMethodPriceQuery
    {
        return $this->getFactory()
            ->createShipmentMethodPriceQuery()
            ->filterByFkShipmentMethod($idShipmentMethod);
    }
}
