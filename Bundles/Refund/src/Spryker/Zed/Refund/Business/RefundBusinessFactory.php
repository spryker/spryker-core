<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Refund\Business\Model\Refund;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;

/**
 * @method RefundQueryContainerInterface getQueryContainer()
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
     * @return SalesFacade
     */
    public function createSalesFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @return OmsFacade
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
