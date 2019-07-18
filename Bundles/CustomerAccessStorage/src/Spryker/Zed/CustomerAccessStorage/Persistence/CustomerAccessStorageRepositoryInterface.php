<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStoragePersistenceFactory getFactory()
 */
interface CustomerAccessStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer;

    /**
     * @return array
     */
    public function findAllCustomerAccessStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $customerAccessStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessStorageEntityTransfer[]
     */
    public function findFilteredCustomerAccessStorageEntities(FilterTransfer $filterTransfer, array $customerAccessStorageEntityIds = []): array;
}
