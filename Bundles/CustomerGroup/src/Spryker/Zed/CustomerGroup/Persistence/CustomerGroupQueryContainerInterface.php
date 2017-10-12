<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

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
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery
     */
    public function queryCustomerGroupToCustomerByFkCustomerGroup($idCustomerGroup);

    /**
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomer();

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupByFkCustomer($idCustomer);
}
