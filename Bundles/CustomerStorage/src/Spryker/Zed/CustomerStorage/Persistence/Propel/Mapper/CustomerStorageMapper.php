<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class CustomerStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorage> $customerInvalidatedStorageEntityCollection
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function mapCustomerInvalidatedStorageEntityCollectionToInvalidatedCustomerCollectionTransfer(
        ObjectCollection $customerInvalidatedStorageEntityCollection,
        InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
    ): InvalidatedCustomerCollectionTransfer {
        foreach ($customerInvalidatedStorageEntityCollection as $customerInvalidatedStorageEntity) {
            $invalidatedCustomerTransfer = (new InvalidatedCustomerTransfer())->fromArray(
                $customerInvalidatedStorageEntity->toArray(),
                true,
            );

            $invalidatedCustomerCollectionTransfer->addInvalidatedCustomer($invalidatedCustomerTransfer);
        }

        return $invalidatedCustomerCollectionTransfer;
    }
}
