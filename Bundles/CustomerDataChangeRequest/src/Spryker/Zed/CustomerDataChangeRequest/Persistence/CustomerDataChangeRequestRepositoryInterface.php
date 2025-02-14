<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;

interface CustomerDataChangeRequestRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function get(CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer): CustomerDataChangeRequestCollectionTransfer;
}
