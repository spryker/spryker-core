<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class BraintreeDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_REFUND = 'refund facade';
    const CURRENCY_MANAGER = 'currency manager';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addRefundFacade($container);
        $container = $this->addCurrencyManager($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container)
    {
        $container[static::FACADE_REFUND] = function (Container $container) {
            return new BraintreeToRefundBridge($container->getLocator()->refund()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyManager(Container $container)
    {
        $container[static::CURRENCY_MANAGER] = function () {
            return CurrencyManager::getInstance();
        };

        return $container;
    }

}
