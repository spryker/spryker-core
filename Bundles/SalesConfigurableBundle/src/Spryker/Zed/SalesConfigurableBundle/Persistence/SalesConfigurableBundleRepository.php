<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Persistence;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundlePersistenceFactory getFactory()
 */
class SalesConfigurableBundleRepository extends AbstractRepository implements SalesConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer {
        $salesOrderConfiguredBundleQuery = $this->getFactory()
            ->getSalesOrderConfiguredBundlePropelQuery()
            ->joinWithSpySalesOrderConfiguredBundleItem();

        $salesOrderConfiguredBundleQuery = $this->setSalesOrderConfiguredBundleFilters(
            $salesOrderConfiguredBundleQuery,
            $salesOrderConfiguredBundleFilterTransfer
        );

        return $this->getFactory()
            ->createSalesOrderConfiguredBundleMapper()
            ->mapBundleEntityCollectionToBundleTransferCollection($salesOrderConfiguredBundleQuery->find());
    }

    /**
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery $salesOrderConfiguredBundleQuery
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
     *
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery
     */
    protected function setSalesOrderConfiguredBundleFilters(
        SpySalesOrderConfiguredBundleQuery $salesOrderConfiguredBundleQuery,
        SalesOrderConfiguredBundleFilterTransfer $salesOrderConfiguredBundleFilterTransfer
    ): SpySalesOrderConfiguredBundleQuery {
        if ($salesOrderConfiguredBundleFilterTransfer->getConfigurableBundleTemplateUuid()) {
            $salesOrderConfiguredBundleQuery->filterByConfigurableBundleTemplateUuid($salesOrderConfiguredBundleFilterTransfer->getConfigurableBundleTemplateUuid());
        }

        if ($salesOrderConfiguredBundleFilterTransfer->getConfigurableBundleTemplateSlotUuid()) {
            $salesOrderConfiguredBundleQuery
                ->useSpySalesOrderConfiguredBundleItemQuery()
                    ->filterByConfigurableBundleTemplateSlotUuid($salesOrderConfiguredBundleFilterTransfer->getConfigurableBundleTemplateSlotUuid())
                ->endUse();
        }

        if ($salesOrderConfiguredBundleFilterTransfer->getSalesOrderItemIds()) {
            $salesOrderConfiguredBundleQuery
                ->useSpySalesOrderConfiguredBundleItemQuery()
                    ->filterByFkSalesOrderItem_In($salesOrderConfiguredBundleFilterTransfer->getSalesOrderItemIds())
                ->endUse();
        }

        return $salesOrderConfiguredBundleQuery;
    }
}
