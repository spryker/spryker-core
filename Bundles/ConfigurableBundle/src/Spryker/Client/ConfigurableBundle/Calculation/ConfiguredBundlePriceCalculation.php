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
        $configuredBundlePriceTransfer = $this->addPrice($configuredBundlePriceTransfer, $itemTransfer);
        $configuredBundlePriceTransfer = $this->addNetPrice($configuredBundlePriceTransfer, $itemTransfer);
        $configuredBundlePriceTransfer = $this->addGrossPrice($configuredBundlePriceTransfer, $itemTransfer);
        $configuredBundlePriceTransfer = $this->addItemSubtotalAggregation($configuredBundlePriceTransfer, $itemTransfer);
        $configuredBundlePriceTransfer = $this->addDiscounts($configuredBundlePriceTransfer, $itemTransfer);
        $configuredBundlePriceTransfer = $this->addItemPriceToPayAggregation($configuredBundlePriceTransfer, $itemTransfer);

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addDiscounts(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitDiscountAmountFullAggregation(
            $configuredBundlePriceTransfer->getUnitDiscountAmountFullAggregation() + $itemTransfer->getUnitDiscountAmountFullAggregation()
        );

        $configuredBundlePriceTransfer->setSumDiscountAmountFullAggregation(
            $configuredBundlePriceTransfer->getSumDiscountAmountFullAggregation() + $itemTransfer->getSumDiscountAmountFullAggregation()
        );

        $configuredBundlePriceTransfer->setUnitDiscountAmountAggregation(
            $configuredBundlePriceTransfer->getUnitDiscountAmountAggregation() + $itemTransfer->getUnitDiscountAmountAggregation()
        );

        $configuredBundlePriceTransfer->setSumDiscountAmountAggregation(
            $configuredBundlePriceTransfer->getSumDiscountAmountAggregation() + $itemTransfer->getSumDiscountAmountAggregation()
        );

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addGrossPrice(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitGrossPrice(
            $configuredBundlePriceTransfer->getUnitGrossPrice() + $itemTransfer->getUnitGrossPrice()
        );

        $configuredBundlePriceTransfer->setSumGrossPrice(
            $configuredBundlePriceTransfer->getSumGrossPrice() + $itemTransfer->getSumGrossPrice()
        );

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addPrice(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitPrice(
            $configuredBundlePriceTransfer->getUnitPrice() + $itemTransfer->getUnitPrice()
        );

        $configuredBundlePriceTransfer->setSumPrice(
            $configuredBundlePriceTransfer->getSumPrice() + $itemTransfer->getSumPrice()
        );

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addNetPrice(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitNetPrice(
            $configuredBundlePriceTransfer->getUnitNetPrice() + $itemTransfer->getUnitNetPrice()
        );

        $configuredBundlePriceTransfer->setSumNetPrice(
            $configuredBundlePriceTransfer->getSumNetPrice() + $itemTransfer->getSumNetPrice()
        );

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addItemSubtotalAggregation(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitSubtotalAggregation(
            $configuredBundlePriceTransfer->getUnitSubtotalAggregation() + $itemTransfer->getUnitSubtotalAggregation()
        );

        $configuredBundlePriceTransfer->setSumSubtotalAggregation(
            $configuredBundlePriceTransfer->getSumSubtotalAggregation() + $itemTransfer->getSumSubtotalAggregation()
        );

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addItemPriceToPayAggregation(ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer, ItemTransfer $itemTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer->setUnitPriceToPayAggregation(
            $configuredBundlePriceTransfer->getUnitPriceToPayAggregation() + $itemTransfer->getUnitPriceToPayAggregation()
        );

        $configuredBundlePriceTransfer->setSumPriceToPayAggregation(
            $configuredBundlePriceTransfer->getSumPriceToPayAggregation() + $itemTransfer->getSumPriceToPayAggregation()
        );

        return $configuredBundlePriceTransfer;
    }
}
