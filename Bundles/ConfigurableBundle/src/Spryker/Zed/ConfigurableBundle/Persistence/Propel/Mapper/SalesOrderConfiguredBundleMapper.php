<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle;

class SalesOrderConfiguredBundleMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle $salesOrderConfiguredBundleEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle
     */
    public function mapSalesOrderConfiguredBundleTransferToSalesOrderConfiguredBundleEntity(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer,
        SpySalesOrderConfiguredBundle $salesOrderConfiguredBundleEntity
    ): SpySalesOrderConfiguredBundle {
        $salesOrderConfiguredBundleEntity->fromArray($salesOrderConfiguredBundleTransfer->modifiedToArray());

        return $salesOrderConfiguredBundleEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle $salesOrderConfiguredBundleEntity
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer
     */
    public function mapSalesOrderConfiguredBundleEntityToSalesOrderConfiguredBundleTransfer(
        SpySalesOrderConfiguredBundle $salesOrderConfiguredBundleEntity,
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): SalesOrderConfiguredBundleTransfer {
        $salesOrderConfiguredBundleTransfer = $salesOrderConfiguredBundleTransfer->fromArray($salesOrderConfiguredBundleEntity->toArray(), true);

        return $salesOrderConfiguredBundleTransfer;
    }
}
