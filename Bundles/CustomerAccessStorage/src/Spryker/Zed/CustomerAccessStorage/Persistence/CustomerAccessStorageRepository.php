<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Propel\Runtime\Collection\ObjectCollection;
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

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities(ObjectCollection $customerAccessEntities): CustomerAccessTransfer
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($customerAccessEntities as $customerAccessEntity) {
            $customerAccessTransfer->addContentTypeAccess(
                (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray(), true)
            );
        }

        return $customerAccessTransfer;
    }
}
