<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment;

use Spryker\Zed\DummyMarketplacePayment\Dependency\Facade\DummyMarketplacePaymentToRefundBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig getConfig()
 */
class DummyMarketplacePaymentDependencyProvider extends AbstractBundleDependencyProvider
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
        $container->set(static::FACADE_REFUND, function (Container $container) {
            return new DummyMarketplacePaymentToRefundBridge($container->getLocator()->refund()->facade());
        });

        return $container;
    }
}
