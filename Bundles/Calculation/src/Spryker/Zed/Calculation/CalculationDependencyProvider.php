<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountAmountAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceToPayAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemProductOptionPriceAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSubtotalAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemTaxAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpensesGrossSumAmountCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ItemGrossAmountsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ProductOptionGrossSumCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CalculationDependencyProvider extends AbstractBundleDependencyProvider
{

    const CALCULATOR_STACK = 'calculator stack';

    const QUOTE_CALCULATOR_PLUGIN_STACK = 'quote calculator plugin stack';
    const ORDER_CALCULATOR_PLUGIN_STACK = 'order calculator plugin stack';

    const FACADE_TAX = 'facade tax';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::CALCULATOR_STACK] = function (Container $container) {
            return $this->getCalculatorStack($container);
        };

        $container[static::QUOTE_CALCULATOR_PLUGIN_STACK] = function (Container $container) {
            return $this->getQuoteCalculatorPluginStack($container);
        };

        $container[static::ORDER_CALCULATOR_PLUGIN_STACK] = function (Container $container) {
            return $this->getOrderCalculatorPluginStack($container);
        };

        $container[static::FACADE_TAX] = function (Container $container) {
            return new CalculationToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     *
     * @deprecated use CalculationDependencyProvider::getQuoteCalculatorPluginStack or CalculationDependencyProvider::getQuoteCalculatorPluginStack respectively
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getCalculatorStack(Container $container)
    {
        return $this->getQuoteCalculatorPluginStack($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getQuoteCalculatorPluginStack(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]
     */
    protected function getOrderCalculatorPluginStack(Container $container)
    {
        return [];
    }


}
