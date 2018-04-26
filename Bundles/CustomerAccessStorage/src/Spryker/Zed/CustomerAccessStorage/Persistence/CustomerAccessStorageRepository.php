<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Generated\Shared\Transfer\CustomerAccessTransfer;
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
        $unauthenticatedCustomerAccess = $this->buildQueryFromCriteria(
            $this->getFactory()
                ->createPropelCustomerAccessQuery()
        )->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess[] $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities(array $customerAccessEntities): CustomerAccessTransfer
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($customerAccessEntities as $customerAccess) {
            $customerAccessTransfer->addContentTypeAccess(
                $this->getFactory()->createCustomerAccessMapper()->mapEntityToTransfer($customerAccess)
            );
        }

        return $customerAccessTransfer;
    }
}
