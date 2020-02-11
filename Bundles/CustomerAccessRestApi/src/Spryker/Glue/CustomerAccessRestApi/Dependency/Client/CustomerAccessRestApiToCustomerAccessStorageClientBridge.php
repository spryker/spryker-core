<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerAccessTransfer;

class CustomerAccessRestApiToCustomerAccessStorageClientBridge implements CustomerAccessRestApiToCustomerAccessStorageClientInterface
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
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->customerAccessStorageClient->getAuthenticatedCustomerAccess();
    }
}
