<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;

interface CustomerStorageClientInterface
{
    /**
     * Specification:
     * - Finds invalidated customers within Storage according to provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer;
}
