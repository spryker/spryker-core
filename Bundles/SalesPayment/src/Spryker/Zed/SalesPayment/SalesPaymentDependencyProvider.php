<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeBridge;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeBridge;

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
     * @var string
     */
    public const PAYMENT_MAP_KEY_BUILDER_STRATEGY_PLUGINS = 'PAYMENT_MAP_KEY_BUILDER_STRATEGY_PLUGINS';

    /**
     * @var string
     */
    public const FACADE_MESSAGE_BROKER = 'FACADE_MESSAGE_BROKER';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addSalesPaymentExpanderPlugins($container);
        $this->addPaymentMapKeyBuilderStrategyPlugins($container);
        $this->addMessageBrokerFacade($container);
        $this->addSalesFacade($container);

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
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface>
     */
    public function getSalesPaymentExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentMapKeyBuilderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PAYMENT_MAP_KEY_BUILDER_STRATEGY_PLUGINS, function () {
            return $this->getPaymentMapKeyBuilderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface>
     */
    protected function getPaymentMapKeyBuilderStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessageBrokerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSAGE_BROKER, function (Container $container) {
            return new SalesPaymentToMessageBrokerFacadeBridge(
                $container->getLocator()->messageBroker()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesPaymentToSalesFacadeBridge(
                $container->getLocator()->sales()->facade(),
            );
        });

        return $container;
    }
}
