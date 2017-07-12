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

    const PAYMENT_HYDRATION_PLUGINS = 'payment hydration plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CHECKOUT_PLUGINS] = function (Container $container) {
            return new CheckoutPluginCollection();
        };

        $container[static::PAYMENT_HYDRATION_PLUGINS] = function (Container $container) {
            return $this->getPaymentHydrationPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface
     */
    public function getPaymentHydrationPlugins()
    {
         return new PaymentHydratorPluginCollection();
    }

}
