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
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderExpander;
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpander;
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Expense\ExpenseExpander;
use Spryker\Zed\MerchantSalesOrder\Business\Expense\ExpenseExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Reader\MerchantSalesOrderReader;
use Spryker\Zed\MerchantSalesOrder\Business\Reader\MerchantSalesOrderReaderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriter;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface;
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
            $this->createMerchantOrderTotalsCreator(),
            $this->getMerchantOrderPostCreatePlugins()
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
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->createMerchantSalesOrderReader());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface
     */
    public function createMerchantOrderTotalsCreator(): MerchantOrderTotalsCreatorInterface
    {
        return new MerchantOrderTotalsCreator($this->getEntityManager(), $this->getCalculationFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriterInterface
     */
    public function createMerchantOrderItemWriter(): MerchantOrderItemWriterInterface
    {
        return new MerchantOrderItemWriter($this->getEntityManager(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Reader\MerchantSalesOrderReaderInterface
     */
    public function createMerchantSalesOrderReader(): MerchantSalesOrderReaderInterface
    {
        return new MerchantSalesOrderReader(
            $this->getSalesFacade(),
            $this->getRepository(),
            $this->getMerchantOrderFilterPlugins(),
            $this->getMerchantOrderExpanderPlugins(),
            $this->getMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface
     */
    public function getCalculationFacade(): MerchantSalesOrderToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantSalesOrderToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface
     */
    public function getSalesFacade(): MerchantSalesOrderToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_SALES);
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface>
     */
    public function getMerchantOrderPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::PLUGINS_MERCHANT_ORDER_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface>
     */
    public function getMerchantOrderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::PLUGINS_MERCHANT_ORDER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface>
     */
    public function getMerchantOrderFilterPlugins(): array
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::PLUGINS_MERCHANT_ORDER_FILTER);
    }
}
