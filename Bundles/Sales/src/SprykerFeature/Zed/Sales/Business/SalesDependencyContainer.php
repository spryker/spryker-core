<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

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

    public function createOrderManager()
    {
        return $this->getFactory()->createModelOrderDetailsManager(
            $this->createSalesQueryContainer()
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
