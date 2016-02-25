<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Refund\Business\Model\Refund;
use Spryker\Zed\Refund\RefundDependencyProvider;

/**
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 */
class RefundBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Refund\Business\Model\Refund
     */
    public function createRefundModel()
    {
        return new Refund(
            $this->getSalesSplitFacade(),
            $this->getOmsFacade(),
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Refund\Business\RefundManager
     */
    public function createRefundManager()
    {
        return new RefundManager(
            $this->getQueryContainer(),
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesSplitInterface
     */
    public function getSalesSplitFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES_SPLIT);
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

}
