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
        $unauthenticatedCustomerAccess = $this->getFactory()
            ->createPropelCustomerAccessQuery()
            ->find();

        return $this->getFactory()
            ->createCustomerAccessStorageMapper()
            ->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }
}
