<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business;

use SprykerFeature\Zed\Sales\Business\Model\OrderReferenceGenerator;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\Validator;
use SprykerFeature\Zed\Sales\Business\Model\Split\Calculator;
use SprykerFeature\Zed\Sales\Business\Model\Split\OrderItem;
use SprykerFeature\Zed\Sales\Business\Model\OrderManager;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Sales\Business\Model\CommentManager;
use SprykerFeature\Zed\Sales\Business\Model\OrderDetailsManager;
use SprykerFeature\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface;
use SprykerFeature\Zed\Sales\Business\Model\Split\ItemInterface;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use SprykerFeature\Zed\Sales\SalesConfig;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * @method SalesConfig getConfig()
 */
class SalesDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createOrderManager()
    {
        return new OrderManager(
            $this->createSalesQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->createReferenceGenerator()
        );
    }

    /**
     * @return CommentManager
     */
    public function createCommentsManager()
    {
        return new CommentManager(
            $this->createSalesQueryContainer()
        );
    }

    /**
     * @return OrderDetailsManager
     */
    public function createOrderDetailsManager()
    {
        return new OrderDetailsManager(
            $this->createSalesQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_PAYMENT_LOGS)
        );
    }

    /**
     * @return SalesQueryContainerInterface
     */
    public function createSalesQueryContainer()
    {
        return $this->getQueryContainer();
    }

    /**
     * @return ItemInterface
     */
    public function createOrderItemSplitter()
    {
        return new OrderItem(
            $this->createSplitValidator(),
            $this->createSalesQueryContainer(),
            new Calculator()
        );
    }

    /**
     * @return ValidatorInterface
     */
    protected function createSplitValidator()
    {
        return new Validator();
    }

    /**
     * @return OrderReferenceGeneratorInterface
     */
    public function createReferenceGenerator()
    {
        $sequenceNumberSettings = $this->getConfig()->getOrderReferenceDefaults();

        return new OrderReferenceGenerator(
            $this->createSequenceNumberFacade(),
            $sequenceNumberSettings
        );
    }

    /**
     * @return SequenceNumberFacade
     */
    protected function createSequenceNumberFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

}
