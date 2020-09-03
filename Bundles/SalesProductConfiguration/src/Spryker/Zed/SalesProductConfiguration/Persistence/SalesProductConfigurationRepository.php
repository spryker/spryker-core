<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationPersistenceFactory getFactory()
 */
class SalesProductConfigurationRepository extends AbstractRepository implements SalesProductConfigurationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[]
     */
    public function getSalesOrderItemConfigurationsByFilter(
        SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
    ): array {
        $salesOrderItemConfigurationQuery = $this->getFactory()
            ->getSalesOrderItemConfigurationPropelQuery();

        $salesOrderItemConfigurationQuery = $this->setSalesOrderItemConfigurationFilters(
            $salesOrderItemConfigurationQuery,
            $salesOrderItemConfigurationFilterTransfer
        );

        return $this->getFactory()
            ->createSalesOrderItemConfigurationMapper()
            ->mapSalesOrderItemConfigurationEntityCollectionToSalesOrderItemConfigurationTransfers($salesOrderItemConfigurationQuery->find());
    }

    /**
     * @param \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery $salesOrderItemConfigurationQuery
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
     *
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery
     */
    protected function setSalesOrderItemConfigurationFilters(
        SpySalesOrderItemConfigurationQuery $salesOrderItemConfigurationQuery,
        SalesOrderItemConfigurationFilterTransfer $salesOrderItemConfigurationFilterTransfer
    ): SpySalesOrderItemConfigurationQuery {
        if ($salesOrderItemConfigurationFilterTransfer->getSalesOrderItemIds()) {
            $salesOrderItemConfigurationQuery
                ->filterByFkSalesOrderItem_In($salesOrderItemConfigurationFilterTransfer->getSalesOrderItemIds());
        }

        return $salesOrderItemConfigurationQuery;
    }
}
