<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationPersistenceFactory getFactory()
 */
class SalesProductConfigurationEntityManager extends AbstractEntityManager implements SalesProductConfigurationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfiguration(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): void
    {
        $salesOrderItemConfigurationTransfer->requireIdSalesOrderItem();

        $salesOrderItemConfigurationEntity = $this->getFactory()
            ->createSalesOrderItemConfigurationMapper()
            ->mapSalesOrderItemConfigurationTransferToSalesOrderItemConfigurationEntity(
                $salesOrderItemConfigurationTransfer,
                new SpySalesOrderItemConfiguration(),
            );

        $salesOrderItemConfigurationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer
     */
    public function saveSalesOrderItemConfigurationByFkSalesOrderItem(
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
    ): SalesOrderItemConfigurationTransfer {
        $salesOrderItemConfigurationEntity = $this->getFactory()
            ->getSalesOrderItemConfigurationPropelQuery()
            ->filterByFkSalesOrderItem($salesOrderItemConfigurationTransfer->getIdSalesOrderItemOrFail())
            ->findOneOrCreate();

        $salesOrderItemConfigurationMapper = $this->getFactory()->createSalesOrderItemConfigurationMapper();
        $salesOrderItemConfigurationEntity = $salesOrderItemConfigurationMapper
            ->mapSalesOrderItemConfigurationTransferToSalesOrderItemConfigurationEntity(
                $salesOrderItemConfigurationTransfer,
                $salesOrderItemConfigurationEntity,
            );

        $salesOrderItemConfigurationEntity->save();

        return $salesOrderItemConfigurationMapper->mapSalesOrderItemConfigurationEntityToSalesOrderItemConfigurationTransfer(
            $salesOrderItemConfigurationEntity,
            $salesOrderItemConfigurationTransfer,
        );
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemConfigurationsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->getSalesOrderItemConfigurationPropelQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
