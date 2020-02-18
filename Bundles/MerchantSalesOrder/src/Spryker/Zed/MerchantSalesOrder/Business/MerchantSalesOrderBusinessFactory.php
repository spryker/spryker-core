<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderCreator;
use Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpander;
use Spryker\Zed\MerchantSalesOrder\Business\OrderItem\OrderItemExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriter;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderTotalsWriter;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderTotalsWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderWriter;
use Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderWriterInterface;

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
            $this->createMerchantOrderWriter(),
            $this->createMerchantOrderItemWriter(),
            $this->createMerchantOrderTotalsWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderWriterInterface
     */
    public function createMerchantOrderWriter(): MerchantOrderWriterInterface
    {
        return new MerchantOrderWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderItemWriterInterface
     */
    public function createMerchantOrderItemWriter(): MerchantOrderItemWriterInterface
    {
        return new MerchantOrderItemWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Writer\MerchantOrderTotalsWriterInterface
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
}
