<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypePersistenceFactory getFactory()
 */
class SalesShipmentTypeEntityManager extends AbstractEntityManager implements SalesShipmentTypeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesShipmentTypeTransfer $salesShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentTypeTransfer
     */
    public function createSalesShipmentType(SalesShipmentTypeTransfer $salesShipmentTypeTransfer): SalesShipmentTypeTransfer
    {
        $salesShipmentTypeMapper = $this->getFactory()->createSalesShipmentTypeMapper();
        $salesShipmentTypeEntity = $salesShipmentTypeMapper->mapSalesShipmentTypeTransferToSalesShipmentTypeEntity(
            $salesShipmentTypeTransfer,
            new SpySalesShipmentType(),
        );

        $salesShipmentTypeEntity->save();

        return $salesShipmentTypeMapper->mapSalesShipmentTypeEntityToSalesShipmentTypeTransfer(
            $salesShipmentTypeEntity,
            $salesShipmentTypeTransfer,
        );
    }

    /**
     * @module Shipment
     *
     * @param int $idSalesShipment
     * @param int $idSalesShipmentType
     *
     * @return void
     */
    public function updateSalesShipmentWithSalesShipmentType(int $idSalesShipment, int $idSalesShipmentType): void
    {
        $salesShipmentEntity = $this->getFactory()
            ->getSalesShipmentPropelQuery()
            ->filterByIdSalesShipment($idSalesShipment)
            ->findOne();

        if ($salesShipmentEntity === null) {
            return;
        }

        $salesShipmentEntity->setFkSalesShipmentType($idSalesShipmentType);
        $salesShipmentEntity->save();
    }
}
