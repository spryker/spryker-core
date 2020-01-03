<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\OrderPaymentsRestApi\OrderPaymentsRestApiConfig getConfig()
 */
class OrderPaymentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_ORDER_PAYMENT_UPDATER = 'PLUGINS_ORDER_PAYMENT_UPDATER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addOrderPaymentUpdaterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPaymentUpdaterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_ORDER_PAYMENT_UPDATER] = function (Container $container) {
            return $this->getOrderPaymentUpdaterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OrderPaymentsRestApiExtension\Dependency\Plugin\OrderPaymentUpdaterPluginInterface[]
     */
    protected function getOrderPaymentUpdaterPlugins(): array
    {
        return [];
    }
}
