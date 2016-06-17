<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Business;

use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\DiscountTotalAmount;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\GrandTotalWithDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpensesWithDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpenseTaxWithDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\DiscountSalesAggregatorConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountSalesAggregatorConnector\DiscountSalesAggregatorConnectorConfig getConfig()
 */
class DiscountSalesAggregatorConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\DiscountTotalAmount
     */
    public function createOrderDiscountTotalAmount()
    {
        return new DiscountTotalAmount();
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts
     */
    public function createItemTotalOrderAggregator()
    {
        return new ItemDiscounts($this->getDiscountQueryContainer());
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderDiscounts
     */
    public function createSalesOrderTotalsAggregator()
    {
        return new OrderDiscounts();
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\GrandTotalWithDiscounts
     */
    public function createSalesOrderGrandTotalAggregator()
    {
        return new GrandTotalWithDiscounts();
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpenseTaxWithDiscounts
     */
    public function createOrderExpenseTaxWithDiscountsAggregator()
    {
        return new OrderExpenseTaxWithDiscounts(
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpensesWithDiscounts
     */
    public function createOrderExpenseWithDiscountsAggregator()
    {
        return new OrderExpensesWithDiscounts($this->getDiscountQueryContainer());
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function getDiscountQueryContainer()
    {
        return $this->getProvidedDependency(DiscountSalesAggregatorConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade\DiscountSalesAggregatorConnectorToTaxInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(DiscountSalesAggregatorConnectorDependencyProvider::FACADE_TAX);
    }

}
