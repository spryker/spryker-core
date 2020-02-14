<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderCreator;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderCreatorInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriter;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriterInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderCreatorInterface
     */
    public function createMerchantSalesOrderCreator(): MerchantSalesOrderCreatorInterface
    {
        return new MerchantSalesOrderCreator(
            $this->createMerchantSalesOrderWriter(),
            $this->createMerchantSalesOrderItemWriter(),
            $this->createMerchantSalesOrderTotalsWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderWriterInterface
     */
    public function createMerchantSalesOrderWriter(): MerchantSalesOrderWriterInterface
    {
        return new MerchantSalesOrderWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriterInterface
     */
    public function createMerchantSalesOrderItemWriter(): MerchantSalesOrderItemWriterInterface
    {
        return new MerchantSalesOrderItemWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriterInterface
     */
    public function createMerchantSalesOrderTotalsWriter(): MerchantSalesOrderTotalsWriterInterface
    {
        return new MerchantSalesOrderTotalsWriter(
            $this->getEntityManager()
        );
    }
}
