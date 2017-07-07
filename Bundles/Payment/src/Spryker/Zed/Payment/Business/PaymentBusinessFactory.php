<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor;
use Spryker\Zed\Payment\Business\Order\SalesPaymentHydrator;
use Spryker\Zed\Payment\Business\Order\SalesPaymentReader;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaver;
use Spryker\Zed\Payment\PaymentDependencyProvider;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 */
class PaymentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutorInterface
     */
    public function createCheckoutPaymentPluginExecutor()
    {
        return new PaymentPluginExecutor($this->getCheckoutPlugins(), $this->createPaymentSaver());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    protected function createPaymentSaver()
    {
        return new SalesPaymentSaver($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface
     */
    public function getCheckoutPlugins()
    {
         return $this->getProvidedDependency(PaymentDependencyProvider::CHECKOUT_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentHydratorInterface
     */
    public function createPaymentHydrator()
    {
        return new SalesPaymentHydrator(
            $this->getPaymentHydrationPlugins(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface[]
     */
    protected function getPaymentHydrationPlugins()
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::PAYMENT_HYDRATION_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentReaderInterface
     */
    public function createSalesPaymentReader()
    {
        return new SalesPaymentReader(
            $this->getQueryContainer(),
            $this->createPaymentHydrator()
        );
    }

}
