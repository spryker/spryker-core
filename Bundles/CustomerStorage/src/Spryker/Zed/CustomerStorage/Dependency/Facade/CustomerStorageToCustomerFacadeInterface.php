<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Dependency\Facade;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;

interface CustomerStorageToCustomerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByCriteria(
        CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
    ): CustomerCollectionTransfer;
}
