<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution\Dependency\Injector;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\StepEngine\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\CheckoutSubFormPluginCollection;
use Spryker\Yves\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Yves\Payolution\Plugin\PayolutionHandlerPlugin;
use Spryker\Yves\Payolution\Plugin\PayolutionInstallmentSubFormPlugin;
use Spryker\Yves\Payolution\Plugin\PayolutionInvoiceSubFormPlugin;

class CheckoutDependencyInjector extends AbstractDependencyInjector
{

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (CheckoutSubFormPluginCollection $paymentSubForms) {
            $paymentSubForms->add(new PayolutionInstallmentSubFormPlugin());
            $paymentSubForms->add(new PayolutionInvoiceSubFormPlugin());

            return $paymentSubForms;
        });

        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (CheckoutStepHandlerPluginCollection $paymentMethodHandler) {
            $payolutionHandlerPlugin = new PayolutionHandlerPlugin();

            $paymentMethodHandler->add($payolutionHandlerPlugin, PaymentTransfer::PAYOLUTION_INVOICE);
            $paymentMethodHandler->add($payolutionHandlerPlugin, PaymentTransfer::PAYOLUTION_INSTALLMENT);

            return $paymentMethodHandler;
        });

        return $container;
    }

}
