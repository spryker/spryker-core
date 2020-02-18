<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderCreator;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpander;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderCreatorInterface
     */
    public function createMerchantOrderCreator(): MerchantOrderCreatorInterface
    {
        return new MerchantOrderCreator(
            $this->createMerchantOrderWriter(),
            $this->createMerchantOrderItemWriter(),
            $this->createMerchantOrderTotalsWriter(),
            $this->getMerchantSalesOrderPostCreatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderWriterInterface
     */
    public function createMerchantOrderWriter(): MerchantOrderWriterInterface
    {
        return new MerchantOrderWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriterInterface
     */
    public function createMerchantOrderItemWriter(): MerchantOrderItemWriterInterface
    {
        return new MerchantOrderItemWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriterInterface
     */
    public function createMerchantOrderTotalsWriter(): MerchantOrderTotalsWriterInterface
    {
        return new MerchantOrderTotalsWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface[]
     */
    public function getMerchantSalesOrderPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::PLUGINS_MERCHANT_SALES_ORDER_POST_CREATE);
    }
}
