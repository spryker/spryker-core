<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomersQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupQueryContainer extends AbstractQueryContainer implements CustomerGroupQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroup()
    {
        return $this->getFactory()
            ->createCustomerGroupQuery();
    }

    /**
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupById($idCustomerGroup)
    {
        return $this->queryCustomerGroup()
            ->filterByIdCustomerGroup($idCustomerGroup);
    }

    /**
     * @api
     *
     * @return SpyCustomerGroupToCustomerQuery
     */
    public function queryCustomerGroupToCustomerByIdCustomerGroup()
    {
        return $this->getFactory()
            ->createCustomerGroupToCustomerQuery();
    }

}
