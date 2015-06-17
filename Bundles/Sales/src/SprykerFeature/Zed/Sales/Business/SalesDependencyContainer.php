<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;

/**
 * @method SalesBusiness getFactory()
 */
class SalesDependencyContainer extends AbstractDependencyContainer
{

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
     * @return OrderManager
     */
//    public function createOrderManager()
//    {
//        return $this->getFactory()->createModelOrderManager(
//            $this->locator,
//            $this->getFactory()
////            $this->createSalesQueryContainer()
//        );
//    }

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
}
