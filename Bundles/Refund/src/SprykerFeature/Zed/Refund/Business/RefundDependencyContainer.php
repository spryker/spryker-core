<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\RefundBusiness;
use Pyz\Zed\Sales\Persistence\SalesQueryContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Refund\Business\Model\Refund;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method Factory|RefundBusiness getFactory()
 */
class RefundDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Refund
     */
    public function createRefundModel()
    {
        return $this->getFactory()->createModelRefund(
            $this->createSalesFacade(),
            $this->createOmsFacade(),
            $this->createSalesQueryContainer()
        );
    }

    /**
     * @return OmsFacade
     */
    protected function createOmsFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_OMS);
    }

    /**
     * @return SalesFacade
     */
    protected function createSalesFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @return SalesQueryContainer
     */
    protected function createSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

}
