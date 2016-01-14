<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Sales\Business\Model\OrderReferenceGenerator;
use Spryker\Zed\Sales\Business\Model\OrderTotalsAggregator;
use Spryker\Zed\Sales\Business\Model\Split\Validation\Validator;
use Spryker\Zed\Sales\Business\Model\Split\Calculator;
use Spryker\Zed\Sales\Business\Model\Split\OrderItem;
use Spryker\Zed\Sales\Business\Model\OrderManager;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Model\CommentManager;
use Spryker\Zed\Sales\Business\Model\OrderDetailsManager;
use Spryker\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface;
use Spryker\Zed\Sales\Business\Model\Split\ItemInterface;
use Spryker\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToRefundInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Tax\Business\TaxFacade;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;

/**
 * @method SalesConfig getConfig()
 * @method SalesQueryContainer getQueryContainer()
 */
class SalesBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderManager
     */
    public function createOrderManager()
    {
        return new OrderManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->createReferenceGenerator(),
            $this->getConfig()
        );
    }

    /**
     * @return CommentManager
     */
    public function createCommentsManager()
    {
        return new CommentManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return OrderDetailsManager
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
     * @return ItemInterface
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
     * @return ValidatorInterface
     */
    protected function createSplitValidator()
    {
        $validator = new Validator();

        return $validator;
    }

    /**
     * @return OrderReferenceGeneratorInterface
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
     * @return TaxFacade
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_TAX);
    }

    /**
     * @return Calculator
     */
    protected function createCalculator()
    {
        $calculator = new Calculator();

        return $calculator;
    }

    /**
     * @return OrderTotalsAggregator
     */
    public function createOrderTotalsAggregator()
    {
        return new OrderTotalsAggregator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @throws ContainerKeyNotFoundException
     *
     * @return SalesToOmsInterface
     */
    public function getFacadeOms()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @throws ContainerKeyNotFoundException
     *
     * @return SalesToRefundInterface
     */
    public function getFacadeRefund()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_REFUND);
    }

}
