<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Calculation;

use Generated\Shared\Transfer\ConfiguredBundlePriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;

class ConfiguredBundlePriceCalculation implements ConfiguredBundlePriceCalculationInterface
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
    ): ConfiguredBundlePriceTransfer {
        $configuredBundlePriceTransfer = new ConfiguredBundlePriceTransfer();

        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            if (isset($itemTransfers[$salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem()])) {
                $configuredBundlePriceTransfer = $this->calculateAmounts(
                    $configuredBundlePriceTransfer,
                    $itemTransfers[$salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem()]
                );
            }
        }

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function calculateAmounts(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitPrice(
            $configuredBundlePriceTransfer->getUnitPrice() + $itemTransfer->getUnitPrice()
        );

        $configuredBundlePriceTransfer->setSumSubtotalAggregation(
            $configuredBundlePriceTransfer->getSumSubtotalAggregation() + $itemTransfer->getSumSubtotalAggregation()
        );

        $configuredBundlePriceTransfer->setSumPriceToPayAggregation(
            $configuredBundlePriceTransfer->getSumPriceToPayAggregation() + $itemTransfer->getSumPriceToPayAggregation()
        );

        return $configuredBundlePriceTransfer;
    }
}
