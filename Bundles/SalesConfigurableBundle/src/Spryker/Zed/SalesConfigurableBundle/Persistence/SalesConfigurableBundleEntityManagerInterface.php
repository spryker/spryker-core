<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Persistence;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

interface SalesConfigurableBundleEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer
     */
    public function createSalesOrderConfiguredBundle(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): SalesOrderConfiguredBundleTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function createSalesOrderConfiguredBundleItem(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function saveSalesOrderConfiguredBundleItemByFkSalesOrderItem(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer;

    /**
     * @param list<int> $salesOrderConfiguredBundleIds
     *
     * @return void
     */
    public function deleteSalesOrderConfiguredBundlesByIds(array $salesOrderConfiguredBundleIds): void;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderConfiguredBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): void;
}
