<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\AmountFormatter;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

class DiscountAmountFormatter implements DiscountAmountFormatterInterface
{
    /**
     * @var array<string, \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    protected $calculatorPlugins = [];

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface
     */
    protected DiscountToStoreFacadeInterface $storeFacade;

    /**
     * @param array<string, \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface> $calculatorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface $storeFacade
     */
    public function __construct(array $calculatorPlugins, DiscountToStoreFacadeInterface $storeFacade)
    {
        $this->calculatorPlugins = $calculatorPlugins;
        $this->storeFacade = $storeFacade;
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
        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        $defaultIsoCode = null;
        if ($this->storeFacade->isDynamicStoreEnabled() && $discountCalculatorTransfer->getMoneyValueCollection()->count() > 0) {
            $moneyValueTransfer = $discountCalculatorTransfer->getMoneyValueCollection()->offsetGet(0);
            $defaultIsoCode = $moneyValueTransfer->getCurrency()->getCode();
        }

        $formatterAmount = $this->formatAmount(
            $calculatorPlugin,
            (int)$discountConfiguratorTransfer->getDiscountCalculator()->getAmount(),
            $defaultIsoCode,
        );

        $discountConfiguratorTransfer->getDiscountCalculator()->setAmount($formatterAmount);

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculatorPlugin
     * @param int $amount
     * @param string|null $isoCode
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
                $moneyValueTransfer->getCurrency()->getCode(),
            );
            $moneyValueTransfer->setGrossAmount($formattedGrossAmount);

            /** @var int|null $formattedNetAmount */
            $formattedNetAmount = $this->formatAmount(
                $calculatorPlugin,
                $moneyValueTransfer->getNetAmount(),
                $moneyValueTransfer->getCurrency()->getCode(),
            );
            $moneyValueTransfer->setNetAmount($formattedNetAmount);
        }

        return $discountConfiguratorTransfer;
    }
}
