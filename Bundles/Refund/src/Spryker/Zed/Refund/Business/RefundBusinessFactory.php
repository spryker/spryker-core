<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Refund\Business\Model\Refund;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Refund\RefundConfig;

/**
 * @method RefundQueryContainerInterface getQueryContainer()
 * @method RefundConfig getConfig()
 */
class RefundBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Refund\Business\Model\Refund
     */
    public function createRefundModel()
    {
        return new Refund(
            $this->getSalesFacade(),
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
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @deprecated Use getSalesFacade() instead.
     *
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    protected function createSalesFacade()
    {
        trigger_error('Deprecated, use getSalesFacade() instead.', E_USER_DEPRECATED);

        return $this->getSalesFacade();
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_OMS);
    }

    /**
     * @deprecated Use getOmsFacade() instead.
     *
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface
     */
    protected function createOmsFacade()
    {
        trigger_error('Deprecated, use getOmsFacade() instead.', E_USER_DEPRECATED);

        return $this->getOmsFacade();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @deprecated Use getSalesQueryContainer() instead.
     *
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    protected function createSalesQueryContainer()
    {
        trigger_error('Deprecated, use getSalesQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getSalesQueryContainer();
    }

}
