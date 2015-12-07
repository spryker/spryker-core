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
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\RemoveAllExpensesCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\SubtotalWithoutItemExpensesTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\ProductOptionPriceToPayCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\TaxTotalsCalculatorPlugin(),
            new \SprykerFeature\Zed\Calculation\Communication\Plugin\ItemTotalPriceCalculatorPlugin(),
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
