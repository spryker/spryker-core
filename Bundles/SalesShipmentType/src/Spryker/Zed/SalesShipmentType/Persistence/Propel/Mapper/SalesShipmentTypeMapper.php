<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType;
use Propel\Runtime\Collection\Collection;

class SalesShipmentTypeMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType> $salesShipmentTypeEntities
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function mapSalesShipmentTypeEntitiesToSalesShipmentTypeTransfers(
        Collection $salesShipmentTypeEntities,
        array $salesShipmentTypeTransfers
    ): array {
        foreach ($salesShipmentTypeEntities as $salesShipmentTypeEntity) {
            $salesShipmentTypeTransfers[] = $this->mapSalesShipmentTypeEntityToSalesShipmentTypeTransfer(
                $salesShipmentTypeEntity,
                new SalesShipmentTypeTransfer(),
            );
        }

        return $salesShipmentTypeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentTypeTransfer $salesShipmentTypeTransfer
     * @param \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType $salesShipmentTypeEntity
     *
     * @return \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType
     */
    public function mapSalesShipmentTypeTransferToSalesShipmentTypeEntity(
        SalesShipmentTypeTransfer $salesShipmentTypeTransfer,
        SpySalesShipmentType $salesShipmentTypeEntity
    ): SpySalesShipmentType {
        $salesShipmentTypeEntity->fromArray($salesShipmentTypeTransfer->modifiedToArray());

        return $salesShipmentTypeEntity;
    }

    /**
     * @param \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType $salesShipmentTypeEntity
     * @param \Generated\Shared\Transfer\SalesShipmentTypeTransfer $salesShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentTypeTransfer
     */
    public function mapSalesShipmentTypeEntityToSalesShipmentTypeTransfer(
        SpySalesShipmentType $salesShipmentTypeEntity,
        SalesShipmentTypeTransfer $salesShipmentTypeTransfer
    ): SalesShipmentTypeTransfer {
        return $salesShipmentTypeTransfer->fromArray($salesShipmentTypeEntity->toArray(), true);
    }
}
