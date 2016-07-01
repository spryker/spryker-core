<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPlugin;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorBridge;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SALES_AGGREGATOR = 'sales aggregator facade';
    const QUERY_CONTAINER_SALES = 'sales query container';
    const PLUGIN_REFUND_CALCULATOR = 'refund calculator plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addRefundCalculatorPlugin($container);
        $container = $this->addSalesAggregatorFacade($container);
        $container = $this->addSalesQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundCalculatorPlugin(Container $container)
    {
        $container[self::PLUGIN_REFUND_CALCULATOR] = function () {
            return new RefundCalculatorPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesAggregatorFacade(Container $container)
    {
        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new RefundToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        return $container;
    }

}
