<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem;

class SalesOrderConfiguredBundleItemMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem $salesOrderConfiguredBundleItemEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem
     */
    public function mapSalesOrderConfiguredBundleItemTransferToSalesOrderConfiguredBundleItemEntity(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer,
        SpySalesOrderConfiguredBundleItem $salesOrderConfiguredBundleItemEntity
    ): SpySalesOrderConfiguredBundleItem {
        $salesOrderConfiguredBundleItemEntity->fromArray($salesOrderConfiguredBundleItemTransfer->modifiedToArray());

        $salesOrderConfiguredBundleItemEntity
            ->setFkSalesOrderConfiguredBundle($salesOrderConfiguredBundleItemTransfer->getIdSalesOrderConfiguredBundle())
            ->setFkSalesOrderItem($salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem());

        return $salesOrderConfiguredBundleItemEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem $salesOrderConfiguredBundleItemEntity
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function mapSalesOrderConfiguredBundleItemEntityToSalesOrderConfiguredBundleItemTransfer(
        SpySalesOrderConfiguredBundleItem $salesOrderConfiguredBundleItemEntity,
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer {
        $salesOrderConfiguredBundleItemTransfer = $salesOrderConfiguredBundleItemTransfer->fromArray($salesOrderConfiguredBundleItemEntity->toArray(), true);

        $salesOrderConfiguredBundleItemTransfer
            ->setIdSalesOrderConfiguredBundle($salesOrderConfiguredBundleItemEntity->getFkSalesOrderConfiguredBundle())
            ->setIdSalesOrderItem($salesOrderConfiguredBundleItemEntity->getFkSalesOrderItem());

        return $salesOrderConfiguredBundleItemTransfer;
    }
}
