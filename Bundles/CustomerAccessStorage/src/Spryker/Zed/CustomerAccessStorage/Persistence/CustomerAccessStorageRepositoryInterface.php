<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Generated\Shared\Transfer\CustomerAccessTransfer;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStoragePersistenceFactory getFactory()
 */
interface CustomerAccessStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer;
}
