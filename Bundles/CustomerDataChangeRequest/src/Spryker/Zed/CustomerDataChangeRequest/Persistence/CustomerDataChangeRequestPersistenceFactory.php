<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence;

use Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery;
use Spryker\Zed\CustomerDataChangeRequest\Persistence\Propel\Mapper\CustomerDataChangeRequestMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestRepositoryInterface getRepository()
 */
class CustomerDataChangeRequestPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery
     */
    public function createCustomerDataChangeRequestQuery(): SpyCustomerDataChangeRequestQuery
    {
        return SpyCustomerDataChangeRequestQuery::create();
    }

    /**
     * @return \Spryker\Zed\CustomerDataChangeRequest\Persistence\Propel\Mapper\CustomerDataChangeRequestMapper
     */
    public function createCustomerDataChangeRequestMapper(): CustomerDataChangeRequestMapper
    {
        return new CustomerDataChangeRequestMapper();
    }
}
