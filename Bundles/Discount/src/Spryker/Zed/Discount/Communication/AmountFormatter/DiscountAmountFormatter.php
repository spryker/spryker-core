<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\AmountFormatter;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

class DiscountAmountFormatter implements DiscountAmountFormatterInterface
{
    /**
     * @var array|\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected $calculatorPlugins = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function format(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $calculatorPluginName = $discountConfiguratorTransfer->getDiscountCalculator()->getCalculatorPlugin();
        if (!isset($this->calculatorPlugins[$calculatorPluginName])) {
            return $discountConfiguratorTransfer;
        }

        $calculatorPlugin = $this->calculatorPlugins[$calculatorPluginName];

        $discountConfiguratorTransfer = $this->formatDiscountMoneyAmounts($discountConfiguratorTransfer, $calculatorPlugin);

        $formatterAmount = $this->formatAmount(
            $calculatorPlugin,
            $discountConfiguratorTransfer->getDiscountCalculator()->getAmount()
        );

        $discountConfiguratorTransfer->getDiscountCalculator()->setAmount($formatterAmount);

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculatorPlugin
     * @param int $amount
     * @param null|string $isoCode
     *
     * @return string
     */
    protected function formatAmount(DiscountCalculatorPluginInterface $calculatorPlugin, $amount, $isoCode = null)
    {
        if (!$amount) {
            return 'N/A';
        }

        return $calculatorPlugin->getFormattedAmount($amount, $isoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculatorPlugin
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function formatDiscountMoneyAmounts(DiscountConfiguratorTransfer $discountConfiguratorTransfer, $calculatorPlugin)
    {
        foreach ($discountConfiguratorTransfer->getDiscountCalculator()->getMoneyValueCollection() as $moneyValueTransfer) {
            $formattedGrossAmount = $this->formatAmount(
                $calculatorPlugin,
                $moneyValueTransfer->getGrossAmount(),
                $moneyValueTransfer->getCurrency()->getCode()
            );
            $moneyValueTransfer->setGrossAmount($formattedGrossAmount);

            $formattedNetAmount = $this->formatAmount(
                $calculatorPlugin,
                $moneyValueTransfer->getNetAmount(),
                $moneyValueTransfer->getCurrency()->getCode()
            );
            $moneyValueTransfer->setNetAmount($formattedNetAmount);
        }
        return $discountConfiguratorTransfer;
    }
}
