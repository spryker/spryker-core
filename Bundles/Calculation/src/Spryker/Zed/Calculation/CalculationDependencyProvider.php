<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilTextBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUOTE_CALCULATOR_PLUGIN_STACK = 'quote calculator plugin stack';
    public const ORDER_CALCULATOR_PLUGIN_STACK = 'order calculator plugin stack';
    public const PLUGINS_QUOTE_POST_RECALCULATE = 'PLUGINS_QUOTE_POST_RECALCULATE';

    public const SERVICE_UTIL_TEXT = 'util text service';

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

        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new CalculationToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container = $this->addQuotePostRecalculatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuotePostRecalculatePlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_POST_RECALCULATE] = function (Container $container) {
            return $this->getQuotePostRecalculatePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getQuoteCalculatorPluginStack(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getOrderCalculatorPluginStack(Container $container)
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CalculationExtension\Dependency\Plugin\QuotePostRecalculatePluginStrategyInterface[]
     */
    protected function getQuotePostRecalculatePlugins(): array
    {
        return [];
    }
}
