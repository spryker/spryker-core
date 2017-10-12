<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Dependency\Injector;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Ratepay\Plugin\RatepayElvSubFormPlugin;
use Spryker\Yves\Ratepay\Plugin\RatepayHandlerPlugin;
use Spryker\Yves\Ratepay\Plugin\RatepayInstallmentSubFormPlugin;
use Spryker\Yves\Ratepay\Plugin\RatepayInvoiceSubFormPlugin;
use Spryker\Yves\Ratepay\Plugin\RatepayPrepaymentSubFormPlugin;
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
            $paymentSubForms->add(new RatepayElvSubFormPlugin());
            $paymentSubForms->add(new RatepayInstallmentSubFormPlugin());
            $paymentSubForms->add(new RatepayInvoiceSubFormPlugin());
            $paymentSubForms->add(new RatepayPrepaymentSubFormPlugin());

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
            $ratepayHandlerPlugin = new RatepayHandlerPlugin();

            $paymentMethodHandler->add($ratepayHandlerPlugin, RatepayConstants::PAYMENT_METHOD_ELV);
            $paymentMethodHandler->add($ratepayHandlerPlugin, RatepayConstants::PAYMENT_METHOD_INSTALLMENT);
            $paymentMethodHandler->add($ratepayHandlerPlugin, RatepayConstants::PAYMENT_METHOD_INVOICE);
            $paymentMethodHandler->add($ratepayHandlerPlugin, RatepayConstants::PAYMENT_METHOD_PREPAYMENT);

            return $paymentMethodHandler;
        });

        return $container;
    }
}
