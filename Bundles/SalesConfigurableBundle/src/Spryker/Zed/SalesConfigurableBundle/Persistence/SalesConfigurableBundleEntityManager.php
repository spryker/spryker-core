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
                new SpySalesOrderConfiguredBundle()
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
                new SpySalesOrderConfiguredBundleItem()
            );

        $salesOrderConfiguredBundleItemEntity->save();
        $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemEntity->getIdSalesOrderConfiguredBundleItem());

        return $salesOrderConfiguredBundleItemTransfer;
    }
}
