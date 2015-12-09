<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation;

use SprykerFeature\Zed\Calculation\Communication\Plugin\ItemTotalPriceCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\TaxTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ProductOptionPriceToPayCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\SubtotalWithoutItemExpensesTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\RemoveAllExpensesCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;
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
            new RemoveTotalsCalculatorPlugin(),
            new RemoveAllExpensesCalculatorPlugin(),
            new ExpenseTotalsCalculatorPlugin(),
            new SubtotalTotalsCalculatorPlugin(),
            new SubtotalWithoutItemExpensesTotalsCalculatorPlugin(),
            new GrandTotalTotalsCalculatorPlugin(),
            new ExpensePriceToPayCalculatorPlugin(),
            new ItemPriceToPayCalculatorPlugin(),
            new ProductOptionPriceToPayCalculatorPlugin(),
            new GrandTotalTotalsCalculatorPlugin(),
            new TaxTotalsCalculatorPlugin(),
            new ItemTotalPriceCalculatorPlugin(),
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
