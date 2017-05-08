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
use Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CalculationDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUOTE_CALCULATOR_PLUGIN_STACK = 'quote calculator plugin stack';
    const ORDER_CALCULATOR_PLUGIN_STACK = 'order calculator plugin stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::QUOTE_CALCULATOR_PLUGIN_STACK] = function (Container $container) {
            return $this->getQuoteCalculatorPluginStack($container);
        };

        $container[static::ORDER_CALCULATOR_PLUGIN_STACK] = function (Container $container) {
            return $this->getOrderCalculatorPluginStack($container);
        };

        return $container;
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
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getOrderCalculatorPluginStack(Container $container)
    {
        return [];
    }


}
