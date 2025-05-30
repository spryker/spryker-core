<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Persistence;

use Orm\Zed\CustomerDiscountConnector\Persistence\SpyCustomerDiscountQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorRepositoryInterface getRepository()
 */
class CustomerDiscountConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerDiscountConnector\Persistence\SpyCustomerDiscountQuery
     */
    public function createCustomerDiscountQuery(): SpyCustomerDiscountQuery
    {
        return SpyCustomerDiscountQuery::create();
    }
}
