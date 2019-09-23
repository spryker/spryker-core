<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends AbstractRepository implements ShipmentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        $shipmentMethodTransfer->requireName()
            ->requireFkShipmentCarrier();

        return !$this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByName($shipmentMethodTransfer->getName())
            ->filterByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod(), Criteria::NOT_EQUAL)
            ->filterByFkShipmentCarrier($shipmentMethodTransfer->getFkShipmentCarrier())
            ->exists();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getShipmentMethodPricesByIdShipmentMethod(int $idShipmentMethod): ArrayObject
    {
        $shipmentMethodPriceEntities = $this->getFactory()
            ->createShipmentMethodPriceQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->find()
            ->getData();

        return $this->getFactory()
            ->createShipmentMethodPricesMapper()
            ->mapShipmentMethodPriceEntitiesToMoneyValueTransfers($shipmentMethodPriceEntities);
    }
}
