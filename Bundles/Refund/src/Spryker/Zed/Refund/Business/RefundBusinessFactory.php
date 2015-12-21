<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Refund\Business\Model\Refund;
use Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;
use Spryker\Zed\Refund\RefundConfig;

/**
 * @method RefundQueryContainerInterface getQueryContainer()
 * @method RefundConfig getConfig()
 */
class RefundBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Refund
     */
    public function createRefundModel()
    {
        return new Refund(
            $this->createSalesFacade(),
            $this->createOmsFacade(),
            $this->createSalesQueryContainer()
        );
    }

    /**
     * @return RefundManager
     */
    public function createRefundManager()
    {
        return new RefundManager(
            $this->getQueryContainer(),
            $this->createSalesQueryContainer()
        );
    }

    /**
     * @return RefundToSalesInterface
     */
    public function createSalesFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @return RefundToOmsInterface
     */
    protected function createOmsFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_OMS);
    }

    /**
     * @return SalesQueryContainer
     */
    protected function createSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

}
