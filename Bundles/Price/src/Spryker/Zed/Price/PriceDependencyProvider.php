<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Money\Communication\Plugin\MoneyPlugin;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductBridge;
use Spryker\Zed\Price\Dependency\Facade\PriceToTouchBridge;

class PriceDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'facade touch';
    const FACADE_PRODUCT = 'product facade';

    const PLUGIN_MONEY = 'money plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new PriceToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new PriceToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $this->addMoneyPlugin($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container[self::PLUGIN_MONEY] = function () {
            return new MoneyPlugin();
        };

        return $container;
    }

}
