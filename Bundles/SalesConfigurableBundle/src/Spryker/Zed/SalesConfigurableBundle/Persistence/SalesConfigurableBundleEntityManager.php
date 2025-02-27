<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Persistence;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundlePersistenceFactory getFactory()
 */
class SalesConfigurableBundleEntityManager extends AbstractEntityManager implements SalesConfigurableBundleEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer
     */
    public function createSalesOrderConfiguredBundle(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): SalesOrderConfiguredBundleTransfer {
        $salesOrderConfiguredBundleEntity = $this->getFactory()
            ->createSalesOrderConfiguredBundleMapper()
            ->mapBundleTransferToBundleEntity(
                $salesOrderConfiguredBundleTransfer,
                new SpySalesOrderConfiguredBundle(),
            );

        $salesOrderConfiguredBundleEntity->save();
        $salesOrderConfiguredBundleTransfer->setIdSalesOrderConfiguredBundle($salesOrderConfiguredBundleEntity->getIdSalesOrderConfiguredBundle());

        return $salesOrderConfiguredBundleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function createSalesOrderConfiguredBundleItem(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer {
        $salesOrderConfiguredBundleItemTransfer
            ->requireIdSalesOrderItem()
            ->requireIdSalesOrderConfiguredBundle();

        $salesOrderConfiguredBundleItemEntity = $this->getFactory()
            ->createSalesOrderConfiguredBundleMapper()
            ->mapBundleItemTransferToBundleItemEntity(
                $salesOrderConfiguredBundleItemTransfer,
                new SpySalesOrderConfiguredBundleItem(),
            );

        $salesOrderConfiguredBundleItemEntity->save();
        $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemEntity->getIdSalesOrderConfiguredBundleItem());

        return $salesOrderConfiguredBundleItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function saveSalesOrderConfiguredBundleItemByFkSalesOrderItem(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer {
        $salesOrderConfiguredBundleMapper = $this->getFactory()->createSalesOrderConfiguredBundleMapper();
        $salesOrderConfiguredBundleItemEntity = $this->getFactory()
            ->getSalesOrderConfiguredBundleItemPropelQuery()
            ->filterByFkSalesOrderItem($salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItemOrFail())
            ->findOneOrCreate();

        $salesOrderConfiguredBundleItemEntity = $salesOrderConfiguredBundleMapper
            ->mapBundleItemTransferToBundleItemEntity(
                $salesOrderConfiguredBundleItemTransfer,
                $salesOrderConfiguredBundleItemEntity,
            );

        $salesOrderConfiguredBundleItemEntity->save();

        return $salesOrderConfiguredBundleMapper->mapBundleItemEntityToBundleItemTransfer(
            $salesOrderConfiguredBundleItemEntity,
            $salesOrderConfiguredBundleItemTransfer,
        );
    }

    /**
     * @param list<int> $salesOrderConfiguredBundleIds
     *
     * @return void
     */
    public function deleteSalesOrderConfiguredBundlesByIds(array $salesOrderConfiguredBundleIds): void
    {
        $this->getFactory()
            ->getSalesOrderConfiguredBundlePropelQuery()
            ->filterByIdSalesOrderConfiguredBundle_In($salesOrderConfiguredBundleIds)
            ->delete();
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderConfiguredBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->getSalesOrderConfiguredBundleItemPropelQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
