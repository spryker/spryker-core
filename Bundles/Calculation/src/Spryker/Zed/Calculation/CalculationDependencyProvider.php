<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Calculation\Communication\Plugin\ExpensesGrossSumAmountCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ItemGrossAmountsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ProductOptionGrossSumCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CalculationDependencyProvider extends AbstractBundleDependencyProvider
{

    const CALCULATOR_STACK = 'calculator stack';

    const CALCULATOR_PLUGIN_STACK = 'calculator plugin stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CALCULATOR_STACK] = function (Container $container) {
            return $this->getCalculatorStack($container);
        };

        $container[static::CALCULATOR_PLUGIN_STACK] = function (Container $container) {
            return $this->getCalculatorPluginStack($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getCalculatorStack(Container $container)
    {
        return [
            //Remove calculated values, start with clean state.
            new RemoveTotalsCalculatorPlugin(),

            //Item calculators
            new ProductOptionGrossSumCalculatorPlugin(),
            new ItemGrossAmountsCalculatorPlugin(),

            //SubTotal
            new SubtotalTotalsCalculatorPlugin(),

            //Expenses (e.g. shipping)
            new ExpensesGrossSumAmountCalculatorPlugin(),
            new ExpenseTotalsCalculatorPlugin(),

            //GrandTotal
            new GrandTotalTotalsCalculatorPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getCalculatorPluginStack(Container $container)
    {
        return [];
    }


}
