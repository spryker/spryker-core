<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeBridge;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeBridge;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeBridge;
use Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\PaymentApp\PaymentAppConfig getConfig()
 */
class PaymentAppDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PAYMENT = 'FACADE_PAYMENT';

    /**
     * @var string
     */
    public const FACADE_CART = 'FACADE_CART';

    /**
     * @var string
     */
    public const FACADE_KERNEL_APP = 'FACADE_KERNEL_APP';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_EXPRESS_CHECKOUT_PAYMENT_REQUEST_PROCESSOR = 'PLUGINS_EXPRESS_CHECKOUT_PAYMENT_REQUEST_PROCESSOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPaymentFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addKernelAppFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addExpressCheckoutPaymentRequestProcessorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, function (Container $container) {
            return new PaymentAppToPaymentFacadeBridge($container->getLocator()->payment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new PaymentAppToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addKernelAppFacade(Container $container): Container
    {
        $container->set(static::FACADE_KERNEL_APP, function (Container $container) {
            return new PaymentAppToKernelAppFacadeBridge($container->getLocator()->kernelApp()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new PaymentAppToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpressCheckoutPaymentRequestProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EXPRESS_CHECKOUT_PAYMENT_REQUEST_PROCESSOR, function () {
            return $this->getExpressCheckoutPaymentRequestProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface>
     */
    protected function getExpressCheckoutPaymentRequestProcessorPlugins(): array
    {
        return [];
    }
}
