<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupQueryContainer extends AbstractQueryContainer implements CustomerGroupQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery
     */
    public function queryCustomerGroupToCustomerByFkCustomerGroup($idCustomerGroup)
    {
        return $this->getFactory()
            ->createCustomerGroupToCustomerQuery()
            ->filterByFkCustomerGroup($idCustomerGroup);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupByFkCustomer($idCustomer)
    {
        return $this->getFactory()
            ->createCustomerGroupQuery()
            ->useSpyCustomerGroupToCustomerQuery()
                ->filterByFkCustomer($idCustomer)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomer()
    {
        return $this->getFactory()
            ->createCustomerQuery();
    }
}
