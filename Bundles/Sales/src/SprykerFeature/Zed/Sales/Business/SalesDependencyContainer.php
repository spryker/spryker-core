<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Sales\Business\Model\CommentManager;
use SprykerFeature\Zed\Sales\Business\Model\OrderDetailsManager;
use SprykerFeature\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface;
use SprykerFeature\Zed\Sales\Business\Model\Split\ItemInterface;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use SprykerFeature\Zed\Sales\SalesConfig;
use SprykerFeature\Zed\Sales\Business\Model\OrderSequenceInterface;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * @method SalesBusiness getFactory()
 * @method SalesConfig getConfig()
 */
class SalesDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createOrderManager()
    {
        return $this->getFactory()->createModelOrderManager(
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
        return $this->getFactory()->createModelCommentManager(
            $this->createSalesQueryContainer()
        );
    }

    /**
     * @return OrderDetailsManager
     */
    public function createOrderDetailsManager()
    {
        return $this->getFactory()->createModelOrderDetailsManager(
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
        return $this->getFactory()->createModelSplitOrderItem(
            $this->createSplitValidator(),
            $this->createSalesQueryContainer(),
            $this->getFactory()->createModelSplitCalculator()
        );
    }

    /**
     * @return ValidatorInterface
     */
    protected function createSplitValidator()
    {
        return $this->getFactory()->createModelSplitValidationValidator();
    }

    /**
     * @return OrderReferenceGeneratorInterface
     */
    public function createReferenceGenerator()
    {
        $sequenceNumberSettings = $this->getConfig()->getOrderReferenceDefaults();

        return $this->getFactory()->createModelOrderReferenceGenerator(
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
