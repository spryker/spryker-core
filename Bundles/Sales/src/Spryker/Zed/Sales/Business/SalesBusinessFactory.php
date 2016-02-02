<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Sales\Business\Model\OrderReferenceGenerator;
use Spryker\Zed\Sales\Business\Model\Split\Validation\Validator;
use Spryker\Zed\Sales\Business\Model\Split\Calculator;
use Spryker\Zed\Sales\Business\Model\Split\OrderItem;
use Spryker\Zed\Sales\Business\Model\OrderManager;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Model\CommentManager;
use Spryker\Zed\Sales\Business\Model\OrderDetailsManager;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;

/**
 * @method SalesConfig getConfig()
 * @method SalesQueryContainer getQueryContainer()
 */
class SalesBusinessFactory extends AbstractBusinessFactory
{

    public function createOrderManager()
    {
        return new OrderManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->createReferenceGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CommentManager
     */
    public function createCommentsManager()
    {
        return new CommentManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderDetailsManager
     */
    public function createOrderDetailsManager()
    {
        return new OrderDetailsManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_PAYMENT_LOGS)
        );
    }

    /**
     * @deprecated Use getQueryContainer() directly instead.
     *
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    public function createSalesQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() directly instead.', E_USER_DEPRECATED);

        return $this->getQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\ItemInterface
     */
    public function createOrderItemSplitter()
    {
        return new OrderItem(
            $this->createSplitValidator(),
            $this->getQueryContainer(),
            $this->createCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface
     */
    protected function createSplitValidator()
    {
        $validator = new Validator();

        return $validator;
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
     * @deprecated Use getSequenceNumberFacade() instead.
     *
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected function createSequenceNumberFacade()
    {
        trigger_error('Deprecated, use getSequenceNumberFacade() instead.', E_USER_DEPRECATED);

        return $this->getSequenceNumberFacade();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\Calculator
     */
    protected function createCalculator()
    {
        $calculator = new Calculator();

        return $calculator;
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getFacadeOms()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToRefundInterface
     */
    public function getFacadeRefund()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_REFUND);
    }

}
