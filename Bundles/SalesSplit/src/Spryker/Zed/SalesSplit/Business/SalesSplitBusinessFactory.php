<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesSplit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesSplit\Business\Model\Calculator;
use Spryker\Zed\SalesSplit\Business\Model\OrderOrderItemSplitSplit;
use Spryker\Zed\SalesSplit\Business\Model\Validation\Validator;
use Spryker\Zed\SalesSplit\SalesSplitDependencyProvider;

/**
 * @method \Spryker\Zed\SalesSplit\SalesSplitConfig getConfig()
 */
class SalesSplitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesSplit\Business\Model\OrderItemSplitInterface
     */
    public function createOrderItemSplitter()
    {
        return new OrderOrderItemSplitSplit(
            $this->createSplitValidator(),
            $this->getSalesQueryContainer(),
            $this->createCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\SalesSplit\Business\Model\Validation\ValidatorInterface
     */
    protected function createSplitValidator()
    {
        return new Validator();
    }

    /**
     * @return \Spryker\Zed\SalesSplit\Business\Model\Calculator
     */
    protected function createCalculator()
    {
        return new Calculator();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
       return $this->getProvidedDependency(SalesSplitDependencyProvider::SALES_QUERY_CONTAINER);
    }
}
