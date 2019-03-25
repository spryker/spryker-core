<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
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
        $customerGroupEntities = $this->getFactory()->createCustomerGroupQuery()
            ->innerJoinSpyCustomerGroupToCustomer()
            ->useSpyCustomerGroupToCustomerQuery()
            ->filterByFkCustomer($idCustomer)
            ->endUse()->find();

        return $this->getFactory()->createCustomerGroupMapper()
            ->mapCustomerGroupEntitiesToCustomerGroupCollectionTransfer($customerGroupEntities);
    }
}
