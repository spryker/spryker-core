<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Dependency\Injector;

use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentCreditCardSubFormPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentHandlerPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentInvoiceSubFormPlugin;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;

class CheckoutDependencyInjector implements DependencyInjectorInterface
{
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function inject(Container $container): Container
    {
        $container = $this->injectPaymentSubForms($container);
        $container = $this->injectPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function injectPaymentSubForms(Container $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubForms) {
            $paymentSubForms->add(new DummyPaymentCreditCardSubFormPlugin());
            $paymentSubForms->add(new DummyPaymentInvoiceSubFormPlugin());

            return $paymentSubForms;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function injectPaymentMethodHandler(Container $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $dummyPaymentHandlerPlugin = new DummyPaymentHandlerPlugin();

            $paymentMethodHandler->add($dummyPaymentHandlerPlugin, DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD);
            $paymentMethodHandler->add($dummyPaymentHandlerPlugin, DummyPaymentConfig::PAYMENT_METHOD_INVOICE);

            return $paymentMethodHandler;
        });

        return $container;
    }
}
