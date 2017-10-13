<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Dependency\Injector;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollection;
use Spryker\Zed\Payment\PaymentDependencyProvider;
use Spryker\Zed\Ratepay\Communication\Plugin\Checkout\RatepayPostCheckPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Checkout\RatepaySaveOrderPlugin;

class PaymentDependencyInjector extends AbstractDependencyInjector
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectBusinessLayerDependencies(Container $container)
    {
        $container = $this->injectPaymentPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectPaymentPlugins(Container $container)
    {
        $container->extend(PaymentDependencyProvider::CHECKOUT_PLUGINS, function (CheckoutPluginCollection $pluginCollection) {
            $pluginCollection->add(new RatepaySaveOrderPlugin(), RatepayConstants::PROVIDER_NAME, PaymentDependencyProvider::CHECKOUT_ORDER_SAVER_PLUGINS);
            $pluginCollection->add(new RatepayPostCheckPlugin(), RatepayConstants::PROVIDER_NAME, PaymentDependencyProvider::CHECKOUT_POST_SAVE_PLUGINS);

            return $pluginCollection;
        });

        return $container;
    }
}
