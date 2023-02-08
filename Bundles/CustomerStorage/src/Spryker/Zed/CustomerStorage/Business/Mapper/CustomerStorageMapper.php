<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Mapper;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Zed\CustomerStorage\CustomerStorageConfig;

class CustomerStorageMapper implements CustomerStorageMapperInterface
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
    ): array {
        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            $customerCollectionArray[$customerTransfer->getIdCustomerOrFail()] = $customerTransfer;
        }

        return $customerCollectionArray;
    }

    /**
     * @param array<int, int> $customerIds
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    public function mapCustomerIdsToCustomerCriteriaFilterTransfer(
        array $customerIds,
        CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
    ): CustomerCriteriaFilterTransfer {
        foreach ($customerIds as $customerId) {
            $customerCriteriaFilterTransfer->addIdCustomer($customerId);
        }

        return $customerCriteriaFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerTransfer $invalidatedCustomerTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function mapInvalidatedCustomerTransferToSynchronizationDataTransfer(
        InvalidatedCustomerTransfer $invalidatedCustomerTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        $invalidatedCustomerTransferData = [
            CustomerStorageConfig::COL_ANONYMIZED_AT => $invalidatedCustomerTransfer->getAnonymizedAt(),
            CustomerStorageConfig::COL_PASSWORD_UPDATED_AT => $invalidatedCustomerTransfer->getPasswordUpdatedAt(),
        ];

        return $synchronizationDataTransfer->setData($invalidatedCustomerTransferData);
    }
}
