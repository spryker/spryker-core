<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector;

use Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyBridge;
use Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToDiscountBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CurrencyDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CURRENCY = 'CURRENCY_FACADE';
    const FACADE_DISCOUNT = 'DISCOUNT_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCurrencyFacade($container);
        $container = $this->addDiscountFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addCurrencyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new CurrencyDiscountConnectorToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDiscountFacade(Container $container)
    {
        $container[static::FACADE_DISCOUNT] = function (Container $container) {
            return new CurrencyDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };

        return $container;
    }

}
