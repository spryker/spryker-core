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
        return new OrderExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface
     */
    public function createMerchantOrderTotalsCreator(): MerchantOrderTotalsCreatorInterface
    {
        return new MerchantOrderTotalsCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }
}
