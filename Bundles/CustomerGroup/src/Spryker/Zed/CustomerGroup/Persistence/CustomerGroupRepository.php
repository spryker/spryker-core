<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Generated\Shared\Transfer\CustomerGroupNamesTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Spryker\Zed\CustomerGroup\Persistence\Mapper\CustomerGroupMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupRepository extends AbstractRepository implements CustomerGroupRepositoryInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupNamesTransfer
     */
    public function findCustomerGroupNamesByIdCustomer(int $idCustomer): CustomerGroupNamesTransfer
    {
        $customerGroupNames = $this->getCustomerQuery()
            ->filterByIdCustomer($idCustomer)
            ->innerJoinSpyCustomerGroupToCustomer()
            ->useSpyCustomerGroupToCustomerQuery()
                ->innerJoinCustomerGroup()
            ->endUse()
            ->select(SpyCustomerGroupTableMap::COL_NAME)
            ->find()
            ->toArray();

        return $this->getCustomerGroupMapper()
            ->mapCustomerGroupNamesToCustomerGroupNamesTransfer($customerGroupNames);
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function getCustomerQuery(): SpyCustomerQuery
    {
        return $this->getFactory()->createCustomerQuery();
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Persistence\Mapper\CustomerGroupMapperInterface
     */
    protected function getCustomerGroupMapper(): CustomerGroupMapperInterface
    {
        return $this->getFactory()->createCustomerGroupMapper();
    }
}
