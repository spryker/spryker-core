<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 */
class SalesPaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SALES_PAYMENT_EXPANDER_PLUGINS = 'SALES_PAYMENT_EXPANDER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addSalesPaymentExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesPaymentExpanderPlugins(Container $container): Container
    {
        $container->set(static::SALES_PAYMENT_EXPANDER_PLUGINS, function () {
            return $this->getSalesPaymentExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface[]
     */
    public function getSalesPaymentExpanderPlugins(): array
    {
        return [];
    }
}
