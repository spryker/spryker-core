<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;

interface ConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer;
}
