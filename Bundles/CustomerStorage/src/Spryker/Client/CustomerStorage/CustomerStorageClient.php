<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CustomerStorage\CustomerStorageFactory getFactory()
 */
class CustomerStorageClient extends AbstractClient implements CustomerStorageClientInterface
{
    /**
     * @inheritDoc
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer {
        return $this->getFactory()
            ->createCustomerStorageReader()
            ->getInvalidatedCustomerCollection($invalidatedCustomerCriteriaTransfer);
    }
}
