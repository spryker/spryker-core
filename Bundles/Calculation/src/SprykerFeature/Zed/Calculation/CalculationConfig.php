<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CalculationConfig extends AbstractBundleConfig
{

    /**
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     */
    public function getCalculatorStack()
    {
        return [
            $this->getLocator()->calculation()->pluginRemoveTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginRemoveAllExpensesCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginExpenseTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginSubtotalTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginSubtotalWithoutItemExpensesTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginGrandTotalTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginExpensePriceToPayCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginItemPriceToPayCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginOptionPriceToPayCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginGrandTotalTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginTaxTotalsCalculatorPlugin(),
            $this->getLocator()->calculation()->pluginItemTotalPriceCalculatorPlugin()
        ];
    }

    /**
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     * @deprecated?
     */
    public function getSoftCalculatorStack()
    {
        return [
        ];
    }

}
