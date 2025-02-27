<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer;

interface SalesOrderConfiguredBundleItemDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionResponseTransfer
     */
    public function deleteSalesOrderConfiguredBundleItemCollection(
        SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer
    ): SalesOrderConfiguredBundleItemCollectionResponseTransfer;
}
