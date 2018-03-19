<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery;

class CustomerAccessStorageQueryContainer implements CustomerAccessStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function queryCustomerAccess()
    {
        return new SpyUnauthenticatedCustomerAccessQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function queryCustomerAccessStorage()
    {
        return new SpyUnauthenticatedCustomerAccessStorageQuery();
    }
}
