<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;

class CustomerAccessQueryContainer implements CustomerAccessQueryContainerInterface
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
}
