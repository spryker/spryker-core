<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Propel\Runtime\Collection\Collection;

class SalesOrderItemConfigurationMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     * @param \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity
     *
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration
     */
    public function mapSalesOrderItemConfigurationTransferToSalesOrderItemConfigurationEntity(
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer,
        SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity
    ): SpySalesOrderItemConfiguration {
        $salesOrderItemConfigurationEntity->fromArray($salesOrderItemConfigurationTransfer->modifiedToArray());
        $salesOrderItemConfigurationEntity->setFkSalesOrderItem($salesOrderItemConfigurationTransfer->getIdSalesOrderItem());

        return $salesOrderItemConfigurationEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection|\Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration[] $salesOrderItemConfigurationEntityCollection
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[]
     */
    public function mapSalesOrderItemConfigurationEntityCollectionToSalesOrderItemConfigurationTransfers(
        Collection $salesOrderItemConfigurationEntityCollection
    ): array {
        $salesOrderItemConfigurationTransfers = [];

        foreach ($salesOrderItemConfigurationEntityCollection as $salesOrderItemConfigurationEntity) {
            $salesOrderItemConfigurationTransfers[] = $this->mapSalesOrderItemConfigurationEntityToSalesOrderItemConfigurationTransfer(
                $salesOrderItemConfigurationEntity,
                new SalesOrderItemConfigurationTransfer()
            );
        }

        return $salesOrderItemConfigurationTransfers;
    }

    /**
     * @param \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer
     */
    protected function mapSalesOrderItemConfigurationEntityToSalesOrderItemConfigurationTransfer(
        SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity,
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
    ): SalesOrderItemConfigurationTransfer {
        $salesOrderItemConfigurationTransfer = $salesOrderItemConfigurationTransfer
            ->fromArray($salesOrderItemConfigurationEntity->toArray(), true);

        $salesOrderItemConfigurationTransfer->setIdSalesOrderItem(
            $salesOrderItemConfigurationEntity->getFkSalesOrderItem()
        );

        return $salesOrderItemConfigurationTransfer;
    }
}
