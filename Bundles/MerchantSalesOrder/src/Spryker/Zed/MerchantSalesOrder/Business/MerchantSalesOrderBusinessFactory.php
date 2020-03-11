<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderCreator;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderItemCreator;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderItemCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreator;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Expense\ExpenseExpander;
use Spryker\Zed\MerchantSalesOrder\Business\Expense\ExpenseExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpander;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderCreatorInterface
     */
    public function createMerchantOrderCreator(): MerchantOrderCreatorInterface
    {
        return new MerchantOrderCreator(
            $this->getEntityManager(),
            $this->createMerchantOrderItemCreator(),
            $this->createMerchantOrderTotalsCreator()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Expense\ExpenseExpanderInterface
     */
    public function createExpenseExpander(): ExpenseExpanderInterface
    {
        return new ExpenseExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderItemCreatorInterface
     */
    public function createMerchantOrderItemCreator(): MerchantOrderItemCreatorInterface
    {
        return new MerchantOrderItemCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface
     */
    public function createMerchantOrderTotalsCreator(): MerchantOrderTotalsCreatorInterface
    {
        return new MerchantOrderTotalsCreator($this->getEntityManager(), $this->getCalculationFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface
     */
    public function getCalculationFacade(): MerchantSalesOrderToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_CALCULATION);
    }
}
