<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Sales\Business\Model\OrderCommentReader;
use Spryker\Zed\Sales\Business\Model\OrderCommentSaver;
use Spryker\Zed\Sales\Business\Model\OrderReferenceGenerator;
use Spryker\Zed\Sales\Business\Model\CustomerOrderReader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Model\OrderDetailsManager;
use Spryker\Zed\Sales\Business\Model\OrderSaver;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainer getQueryContainer()
 */
class SalesBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CustomerOrderReaderInterface
     */
    public function createCustomerOrderReader()
    {
        return new CustomerOrderReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderSaverInterface
     */
    public function createOrderSaver()
    {
        return new OrderSaver(
            $this->getCountryFacade(),
            $this->getOmsFacade(),
            $this->createReferenceGenerator(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderCommentReaderInterface
     */
    public function createOrderCommentReader()
    {
        return new OrderCommentReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderCommentSaverInterface
     */
    public function createOrderCommentSaver()
    {
        return new OrderCommentSaver($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderDetailsManager
     */
    public function createOrderDetailsManager()
    {
        return new OrderDetailsManager(
            $this->getQueryContainer(),
            $this->getOmsFacade(),
            $this->getPaymentLogPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface
     */
    public function createReferenceGenerator()
    {
        $sequenceNumberSettings = $this->getConfig()->getOrderReferenceDefaults();

        return new OrderReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $sequenceNumberSettings
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return array
     */
    protected function getPaymentLogPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_PAYMENT_LOGS);
    }


}
