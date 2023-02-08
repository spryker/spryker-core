<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Persistence;

use Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery;
use Spryker\Zed\CustomerStorage\Persistence\Propel\Mapper\CustomerStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerStorage\CustomerStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 */
class CustomerStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    public function createSpyCustomerInvalidatedStorageQuery(): SpyCustomerInvalidatedStorageQuery
    {
        return SpyCustomerInvalidatedStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CustomerStorage\Persistence\Propel\Mapper\CustomerStorageMapper
     */
    public function createCustomerStorageMapper(): CustomerStorageMapper
    {
        return new CustomerStorageMapper();
    }
}
