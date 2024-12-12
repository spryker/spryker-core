<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PaymentApp\Business\Customer\PaymentCustomer;
use Spryker\Zed\PaymentApp\Business\Customer\PaymentCustomerInterface;
use Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpander;
use Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpanderInterface;
use Spryker\Zed\PaymentApp\Business\PreOrderPayment\PreOrderPayment;
use Spryker\Zed\PaymentApp\Business\PreOrderPayment\PreOrderPaymentInterface;
use Spryker\Zed\PaymentApp\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutor;
use Spryker\Zed\PaymentApp\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutorInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface;
use Spryker\Zed\PaymentApp\PaymentAppDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentApp\PaymentAppConfig getConfig()
 */
class PaymentAppBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PaymentApp\Business\PreOrderPayment\PreOrderPaymentInterface
     */
    public function createPreOrderPayment(): PreOrderPaymentInterface
    {
        return new PreOrderPayment($this->getPaymentFacade(), $this->createExpressCheckoutPaymentRequestExecutor());
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Business\RequestExecutor\ExpressCheckoutPaymentRequestExecutorInterface
     */
    public function createExpressCheckoutPaymentRequestExecutor(): ExpressCheckoutPaymentRequestExecutorInterface
    {
        return new ExpressCheckoutPaymentRequestExecutor(
            $this->createQuotePaymentExpander(),
            $this->getCartFacade(),
            $this->getExpressCheckoutPaymentRequestProcessorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Business\Expander\QuotePaymentExpanderInterface
     */
    public function createQuotePaymentExpander(): QuotePaymentExpanderInterface
    {
        return new QuotePaymentExpander($this->getPaymentFacade());
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface
     */
    public function getPaymentFacade(): PaymentAppToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeInterface
     */
    public function getKernelAppFacade(): PaymentAppToKernelAppFacadeInterface
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::FACADE_KERNEL_APP);
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToCartFacadeInterface
     */
    public function getCartFacade(): PaymentAppToCartFacadeInterface
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PaymentAppToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return list<\Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface>
     */
    public function getExpressCheckoutPaymentRequestProcessorPlugins(): array
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::PLUGINS_EXPRESS_CHECKOUT_PAYMENT_REQUEST_PROCESSOR);
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Business\Customer\PaymentCustomerInterface
     */
    public function createPaymentCustomer(): PaymentCustomerInterface
    {
        return new PaymentCustomer(
            $this->getPaymentFacade(),
            $this->getKernelAppFacade(),
            $this->getUtilEncodingService(),
        );
    }
}
