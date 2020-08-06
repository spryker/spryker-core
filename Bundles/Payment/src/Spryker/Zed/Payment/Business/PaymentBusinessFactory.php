<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payment\Business\Calculation\PaymentCalculator;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor;
use Spryker\Zed\Payment\Business\Method\PaymentMethodFinder;
use Spryker\Zed\Payment\Business\Method\PaymentMethodFinderInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReader;
use Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdater;
use Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodUpdater;
use Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface;
use Spryker\Zed\Payment\Business\Method\PaymentMethodValidator;
use Spryker\Zed\Payment\Business\Method\PaymentMethodValidatorInterface;
use Spryker\Zed\Payment\Business\Order\SalesPaymentHydrator;
use Spryker\Zed\Payment\Business\Order\SalesPaymentReader;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaver;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface;
use Spryker\Zed\Payment\PaymentDependencyProvider;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
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
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface
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
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface
     */
    public function createPaymentMethodReader()
    {
        return new PaymentMethodReader(
            $this->getPaymentMethodFilterPlugins(),
            $this->getConfig(),
            $this->getStoreFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodValidatorInterface
     */
    public function createPaymentMethodValidator(): PaymentMethodValidatorInterface
    {
        return new PaymentMethodValidator($this->createPaymentMethodReader());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Calculation\PaymentCalculatorInterface
     */
    public function createPaymentCalculator()
    {
        return new PaymentCalculator();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodFinderInterface
     */
    public function createPaymentMethodFinder(): PaymentMethodFinderInterface
    {
        return new PaymentMethodFinder($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface
     */
    public function createPaymentMethodStoreRelationUpdater(): PaymentMethodStoreRelationUpdaterInterface
    {
        return new PaymentMethodStoreRelationUpdater(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface
     */
    public function createPaymentMethodUpdater(): PaymentMethodUpdaterInterface
    {
        return new PaymentMethodUpdater(
            $this->getEntityManager(),
            $this->createPaymentMethodStoreRelationUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins()
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::PAYMENT_METHOD_FILTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface
     */
    protected function getStoreFacade(): PaymentToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_STORE);
    }
}
