<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Dependency\Injector;

use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentCreditCardSubFormPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentHandlerPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentInvoiceSubFormPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;

class CheckoutDependencyInjector implements DependencyInjectorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $container = $this->injectPaymentSubForms($container);
        $container = $this->injectPaymentMethodHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentSubForms(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubForms) {
            $paymentSubForms->add(new DummyPaymentCreditCardSubFormPlugin());
            $paymentSubForms->add(new DummyPaymentInvoiceSubFormPlugin());

            return $paymentSubForms;
        });

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectPaymentMethodHandler(ContainerInterface $container)
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
