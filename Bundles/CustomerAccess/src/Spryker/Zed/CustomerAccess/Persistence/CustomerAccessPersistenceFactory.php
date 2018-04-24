<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Spryker\Zed\CustomerAccess\Persistence\Propel\Mapper\CustomerAccessMapper;
use Spryker\Zed\CustomerAccess\Persistence\Propel\Mapper\CustomerAccessMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CustomerAccessPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function createPropelCustomerAccessQuery(): SpyUnauthenticatedCustomerAccessQuery
    {
        return SpyUnauthenticatedCustomerAccessQuery::create();
    }

    /**
     * @return CustomerAccessMapperInterface
     */
    public function createCustomerAccessMapper(): CustomerAccessMapperInterface
    {
        return new CustomerAccessMapper();
    }
}