<?php

namespace Spryker\Client\CustomerAccessStorage;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessStorageClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer;
}