<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesMerchantConnector\Persistence\Propel\Mapper\SalesMerchantConnectorMapper;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorRepositoryInterface getRepository()
 */
class SalesMerchantConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery
     */
    public function createSalesOrderMerchantQuery(): SpySalesOrderMerchantQuery
    {
        return SpySalesOrderMerchantQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Persistence\Propel\Mapper\SalesMerchantConnectorMapper
     */
    public function createSalesMerchantConnectorMapper(): SalesMerchantConnectorMapper
    {
        return new SalesMerchantConnectorMapper();
    }
}
