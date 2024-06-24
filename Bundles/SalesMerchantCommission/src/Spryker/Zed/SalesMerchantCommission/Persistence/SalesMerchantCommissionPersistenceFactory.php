<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Persistence;

use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesMerchantCommission\Persistence\Propel\Mapper\SalesMerchantCommissionMapper;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionEntityManagerInterface getEntityManager()
 */
class SalesMerchantCommissionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery
     */
    public function getSalesMerchantCommissionQuery(): SpySalesMerchantCommissionQuery
    {
        return SpySalesMerchantCommissionQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesMerchantCommission\Persistence\Propel\Mapper\SalesMerchantCommissionMapper
     */
    public function createSalesMerchantCommissionMapper(): SalesMerchantCommissionMapper
    {
        return new SalesMerchantCommissionMapper();
    }
}
