<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerMapper
{
    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[] $customerEntityCollection
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function mapCustomerEntityCollectionToCustomerTransferCollection(array $customerEntityCollection, CustomerCollectionTransfer $customerCollectionTransfer): CustomerCollectionTransfer
    {
        foreach ($customerEntityCollection as $customerEntity) {
            $customerCollectionTransfer->addCustomer(
                (new CustomerTransfer())->fromArray($customerEntity->toArray(), true)
            );
        }

        return $customerCollectionTransfer;
    }
}
