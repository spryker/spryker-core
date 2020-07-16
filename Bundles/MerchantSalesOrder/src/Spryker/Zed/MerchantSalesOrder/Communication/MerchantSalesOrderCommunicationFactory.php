<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 */
class MerchantSalesOrderCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface
     */
    public function getSalesFacade(): MerchantSalesOrderToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_SALES);
    }
}
