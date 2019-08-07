<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Calculator;

use Generated\Shared\Transfer\ConfiguredBundlePriceTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

interface ConfiguredBundlePriceCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    public function calculateSalesOrderConfiguredBundlePrice(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer,
        array $itemTransfers
    ): ConfiguredBundlePriceTransfer;
}
