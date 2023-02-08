<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Mapper;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;

interface CustomerStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     * @param array<int, \Generated\Shared\Transfer\CustomerTransfer> $customerCollectionArray
     *
     * @return array<int, \Generated\Shared\Transfer\CustomerTransfer>
     */
    public function mapCustomerCollectionTransferToCustomerCollectionArrayIndexedByIdCustomer(
        CustomerCollectionTransfer $customerCollectionTransfer,
        array $customerCollectionArray
    ): array;

    /**
     * @param array<int, int> $customerIds
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    public function mapCustomerIdsToCustomerCriteriaFilterTransfer(
        array $customerIds,
        CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
    ): CustomerCriteriaFilterTransfer;
}
