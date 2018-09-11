<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupRepository extends AbstractRepository implements CustomerGroupRepositoryInterface
{
    /**
     * @module Customer
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function getCustomerGroupCollectionByIdCustomer(int $idCustomer): CustomerGroupCollectionTransfer
    {
        $customerGroupNames = $this->getCustomerQuery()
            ->filterByIdCustomer($idCustomer)
            ->innerJoinSpyCustomerGroupToCustomer()
            ->useSpyCustomerGroupToCustomerQuery()
                ->innerJoinCustomerGroup()
            ->endUse()
            ->select([
                SpyCustomerGroupTableMap::COL_NAME,
                SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP,
                SpyCustomerGroupTableMap::COL_DESCRIPTION,
            ])
            ->find()
            ->toArray();

        return $this->getFactory()->createCustomerGroupMapper()
            ->mapCustomerGroupNamesToCustomerGroupCollectionTransfer($customerGroupNames);
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function getCustomerQuery(): SpyCustomerQuery
    {
        return $this->getFactory()->createCustomerQuery();
    }
}
