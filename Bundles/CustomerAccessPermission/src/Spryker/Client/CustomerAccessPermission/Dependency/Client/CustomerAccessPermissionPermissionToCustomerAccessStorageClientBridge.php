<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission\Dependency\Client;

use Generated\Shared\Transfer\CustomerAccessTransfer;

class CustomerAccessPermissionPermissionToCustomerAccessStorageClientBridge implements CustomerAccessPermissionToCustomerAccessStorageClientInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageClientInterface
     */
    protected $customerAccessStorageClient;

    /**
     * @param \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageClientInterface $customerAccessStorageClient
     */
    public function __construct($customerAccessStorageClient)
    {
        $this->customerAccessStorageClient = $customerAccessStorageClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->customerAccessStorageClient->getUnauthenticatedCustomerAccess();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->customerAccessStorageClient->getAuthenticatedCustomerAccess();
    }
}
