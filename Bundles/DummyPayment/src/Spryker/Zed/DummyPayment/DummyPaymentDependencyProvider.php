<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment;

use Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DummyPayment\DummyPaymentConfig getConfig()
 */
class DummyPaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_REFUND = 'refund facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addRefundFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container)
    {
        $container[self::FACADE_REFUND] = function (Container $container) {
            return new DummyPaymentToRefundBridge($container->getLocator()->refund()->facade());
        };

        return $container;
    }
}
