<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollection;
use Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollection;

class PaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    const CHECKOUT_PLUGINS = 'checkout plugins';
    const CHECKOUT_PRE_CHECK_PLUGINS = 'pre check';
    const CHECKOUT_ORDER_SAVER_PLUGINS = 'order saver';
    const CHECKOUT_POST_SAVE_PLUGINS = 'post save';
    const PAYMENT_METHOD_FILTER_PLUGINS = 'PAYMENT_METHOD_FILTER_PLUGINS';

    const PAYMENT_HYDRATION_PLUGINS = 'payment hydration plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCheckoutPlugins($container);
        $container = $this->addPaymentHydrationPlugins($container);
        $container = $this->addPaymentMethodFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutPlugins(Container $container)
    {
        $container[static::CHECKOUT_PLUGINS] = function (Container $container) {
            return new CheckoutPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentHydrationPlugins(Container $container)
    {
        $container[static::PAYMENT_HYDRATION_PLUGINS] = function (Container $container) {
            return $this->getPaymentHydrationPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentMethodFilterPlugins(Container $container)
    {
        $container[static::PAYMENT_METHOD_FILTER_PLUGINS] = function (Container $container) {
            return $this->getPaymentMethodFilterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface
     */
    public function getPaymentHydrationPlugins()
    {
         return new PaymentHydratorPluginCollection();
    }
}
