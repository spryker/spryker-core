<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Payolution\Dependency\Injector;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Yves\Payolution\Plugin\PayolutionHandlerPlugin;
use Spryker\Yves\Payolution\Plugin\PayolutionInstallmentSubFormPlugin;
use Spryker\Yves\Payolution\Plugin\PayolutionInvoiceSubFormPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;

class CheckoutDependencyInjector extends AbstractDependencyInjector
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
            $paymentSubForms->add(new PayolutionInstallmentSubFormPlugin());
            $paymentSubForms->add(new PayolutionInvoiceSubFormPlugin());

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
            $payolutionHandlerPlugin = new PayolutionHandlerPlugin();

            $paymentMethodHandler->add($payolutionHandlerPlugin, PaymentTransfer::PAYOLUTION_INVOICE);
            $paymentMethodHandler->add($payolutionHandlerPlugin, PaymentTransfer::PAYOLUTION_INSTALLMENT);

            return $paymentMethodHandler;
        });

        return $container;
    }

}
