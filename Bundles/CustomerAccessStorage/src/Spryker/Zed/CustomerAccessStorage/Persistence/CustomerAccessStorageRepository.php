<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStoragePersistenceFactory getFactory()
 */
class CustomerAccessStorageRepository extends AbstractRepository implements CustomerAccessStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $unauthenticatedCustomerAccess = $this->getFactory()
            ->createPropelCustomerAccessQuery()
            ->find();

        return $this->getFactory()
            ->createCustomerAccessStorageMapper()
            ->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }

    /**
     * @deprecated Use findFilteredCustomerAccessStorageEntities instead.
     *
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage[]
     */
    public function findAllCustomerAccessStorageEntities(): array
    {
        $entities = $this->getFactory()
            ->createCustomerAccessStorageQuery()
            ->find();

        return $entities->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $customerAccessStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessStorageEntityTransfer[]
     */
    public function findFilteredCustomerAccessStorageEntities(FilterTransfer $filterTransfer, array $customerAccessStorageEntityIds = []): array
    {
        $query = $this->getFactory()
            ->createCustomerAccessStorageQuery();

        if ($customerAccessStorageEntityIds) {
            $query->filterByIdUnauthenticatedCustomerAccessStorage_In($customerAccessStorageEntityIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
