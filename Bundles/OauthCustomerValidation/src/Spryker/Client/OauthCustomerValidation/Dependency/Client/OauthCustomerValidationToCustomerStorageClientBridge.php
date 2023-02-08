<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation\Dependency\Client;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;

class OauthCustomerValidationToCustomerStorageClientBridge implements OauthCustomerValidationToCustomerStorageClientInterface
{
    /**
     * @var \Spryker\Client\CustomerStorage\CustomerStorageClientInterface
     */
    protected $customerStorageClient;

    /**
     * @param \Spryker\Client\CustomerStorage\CustomerStorageClientInterface $customerStorageClient
     */
    public function __construct($customerStorageClient)
    {
        $this->customerStorageClient = $customerStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer {
        return $this->customerStorageClient->getInvalidatedCustomerCollection($invalidatedCustomerCriteriaTransfer);
    }
}
