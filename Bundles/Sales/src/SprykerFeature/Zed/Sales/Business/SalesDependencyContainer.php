<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Sales\Business\Model\CommentManager;
use SprykerFeature\Zed\Sales\Business\Model\OrderDetailsManager;
use SprykerFeature\Zed\Sales\Dependency\Plugin\OrderReferenceGeneratorInterface;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use SprykerFeature\Zed\Sales\SalesConfig;

/**
 * @method SalesBusiness getFactory()
 * @method SalesConfig getConfig()
 */
class SalesDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createOrderManager()
    {
        return $this->getFactory()->createModelOrderManager(
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
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
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
     * @return OrderReferenceGeneratorInterface
     */
    protected function createReferenceGenerator()
    {
        return $this->getFactory()->createModelOrderReferenceGenerator(
            $this->getConfig()->getMinimumOrderNumber(),
            $this->getConfig()->getOrderNumberIncrementMin(),
            $this->getConfig()->getOrderNumberIncrementMax()
        );
    }

}
