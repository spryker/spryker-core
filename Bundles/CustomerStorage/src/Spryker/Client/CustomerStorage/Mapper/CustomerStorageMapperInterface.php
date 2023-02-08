<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage\Mapper;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;

interface CustomerStorageMapperInterface
{
    /**
     * @param array<string, mixed> $customerInvalidatedStorageDataCollectionIndexedByCustomerReference
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function mapCustomerInvalidatedStorageDataCollectionToInvalidatedCustomerCollectionTransfer(
        array $customerInvalidatedStorageDataCollectionIndexedByCustomerReference,
        InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
    ): InvalidatedCustomerCollectionTransfer;
}
