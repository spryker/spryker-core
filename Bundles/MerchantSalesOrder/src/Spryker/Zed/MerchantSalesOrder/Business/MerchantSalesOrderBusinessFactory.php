<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpander;
use Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderWriter\MerchantSalesOrderWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderWriter\MerchantSalesOrderWriterInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderWriter\MerchantSalesOrderWriterInterface
     */
    public function createMerchantSalesOrderWriter(): MerchantSalesOrderWriterInterface
    {
        return new MerchantSalesOrderWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }
}
