<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Calculation\Communication\Plugin\ItemTotalPriceCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\TaxTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ProductOptionPriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalWithoutItemExpensesTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveAllExpensesCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
