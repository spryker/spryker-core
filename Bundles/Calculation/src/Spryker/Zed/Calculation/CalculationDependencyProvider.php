<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Container;

class CalculationDependencyProvider extends AbstractBundleDependencyProvider
{

    const CALCULATOR_STACK = 'calculator stack';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CALCULATOR_STACK] = function (Container $container) {
            return $this->getCalculatorStack($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return CalculatorPluginInterface[]
     */
    protected function getCalculatorStack(Container $container)
    {
        return [
            #Remove calculated values, start with clean state.
            $container->getLocator()->calculation()->pluginRemoveTotalsCalculatorPlugin(),
            $container->getLocator()->calculation()->pluginRemoveAllExpensesCalculatorPlugin(),
            $container->getLocator()->discountCalculationConnector()->pluginRemoveAllCalculatedDiscountsCalculatorPlugin(),

            #Item calculators
            $container->getLocator()->calculation()->pluginProductOptionGrossSumCalculatorPlugin(),
            $container->getLocator()->calculation()->pluginItemGrossAmountsCalculatorPlugin(),

            #SubTotal
            $container->getLocator()->calculation()->pluginSubtotalTotalsCalculatorPlugin(),

            #Expenses (e.g. shipping)
            $container->getLocator()->calculation()->pluginExpenseTotalsCalculatorPlugin(),

            #GrandTotal
            $container->getLocator()->calculation()->pluginGrandTotalTotalsCalculatorPlugin(),

            #TaxTotal
            $container->getLocator()->tax()->pluginTaxTotalsCalculatorPlugin(),

        ];
    }

}
