<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

interface ConfigurableBundleEntityManagerInterface
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
}
