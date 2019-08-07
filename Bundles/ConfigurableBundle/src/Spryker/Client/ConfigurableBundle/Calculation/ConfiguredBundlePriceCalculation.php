<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Calculation;

use Generated\Shared\Transfer\ConfiguredBundlePriceTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ConfiguredBundlePriceCalculation implements ConfiguredBundlePriceCalculationInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    public function calculateConfiguredBundlePrice(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer = new ConfiguredBundlePriceTransfer();

        foreach ($configuredBundleTransfer->getItems() as $itemTransfer) {
            $configuredBundlePriceTransfer = $this->calculateAmounts($configuredBundlePriceTransfer, $itemTransfer);
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
