<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CustomerGroupQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroup();

    /**
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupById($idCustomerGroup);

    /**
     * @api
     *
     * @return SpyCustomerGroupToCustomerQuery
     */
    public function queryCustomerGroupToCustomerByIdCustomerGroup();

}
