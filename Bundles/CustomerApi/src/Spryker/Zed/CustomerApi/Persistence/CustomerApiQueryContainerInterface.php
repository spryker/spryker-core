<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CustomerApiQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryFind();

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryGet($idCustomer);

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryRemove($idCustomer);
}
